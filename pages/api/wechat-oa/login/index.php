<?php

use Miaoxing\Plugin\BasePage;
use Miaoxing\Plugin\Service\User;
use Miaoxing\User\Service\UserModel;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;
use Miaoxing\WechatOa\Service\WechatOaApi;
use Miaoxing\WechatOa\Service\WechatOaUserModel;
use Wei\Ret;

return new /**
 * @mixin UrlMixin
 * @mixin LoggerMixin
 */
class extends BasePage {
    protected const MAX_RETRY = 3;
    protected $controllerAuth = false;

    public function get()
    {
        $ret = $this->getAccount();
        if ($ret->isErr()) {
            return $ret;
        }
        $account = $ret['data'];

        $url = $account->getOauth2Url($this->req['url']);

        return suc([
            'url' => $url,
        ]);
    }

    public function post($req)
    {
        // 1. code 换取 OpenID
        $api = WechatOaApi::instance();
        $ret = $api->getSnsOAuth2AccessToken(['code' => $req['code']]);
        if (!$ret->isSuc()) {
            $ret->setMessage(sprintf('很抱歉，微信授权失败，请返回再试。(%s)', $ret['message']));
            $ret->set('retryUrl', $this->getRetryUrl($api->getAccount()));
            return $ret;
        }
        $this->logIfRetry();

        // 2. 创建用户并登录
        $oaUser = WechatOaUserModel::findOrInitBy(['openId' => $ret['openid']]);
        if (isset($ret['unionid'])) {
            $oaUser->unionId = $ret['unionid'];
        }

        if ('snsapi_userinfo' === $ret['scope']) {
            $ret = $api->getSnsUserInfo(['access_token' => $ret['access_token'], 'openid' => $ret['openid']]);
            if ($ret->isSuc()) {
                $oaUser->nickName = $ret['nickname'];
                $oaUser->sex = $ret['sex'];
                $oaUser->language = $ret['language'];
                $oaUser->city = $ret['city'];
                $oaUser->province = $ret['province'];
                $oaUser->country = $ret['country'];
                $oaUser->headImgUrl = $ret['headimgurl'];
                $oaUser->privilege = $ret['privilege'];
            }
        }

        if ($oaUser->isNew()) {
            $user = UserModel::save();
            $oaUser->userId = $user->id;
        } else {
            $user = $oaUser->user;
        }

        $oaUser->save();

        $ret = User::loginByModel($user);

        return $ret;
    }

    /**
     * @return Ret|array{data:WechatOaAccountModel}
     */
    protected function getAccount()
    {
        $account = WechatOaAccountModel::findBy('type', WechatOaAccountModel::TYPE_SERVICE);
        if (!$account || !$account->applicationId || !$account->applicationSecret) {
            return err('未设置服务号');
        }
        return suc(['data' => $account]);
    }

    protected function getRetryUrl(WechatOaAccountModel $account): ?string
    {
        $retry = 1;
        $url = $this->getUrl();
        $this->logger->debug('Wechat OAuth retry from url', $url);

        $components = parse_url($url);
        if (isset($components['query'])) {
            parse_str($components['query'], $params);
            if (isset($params['retry'])) {
                $retry = $params['retry'] + 1;
                unset($params['retry']);
            }

            // 移除地址中已有的 code 和 state
            unset($params['code'], $params['state']);
        } else {
            $params = [];
            $components['query'] = '';
        }

        if ($retry > self::MAX_RETRY) {
            // 超过三次，不再返回
            return null;
        }

        $params['retry'] = $retry;
        $components['query'] = http_build_query($params);

        $url = $components['scheme'] . '://' . $components['host']
            . (isset($components['port']) ? (':' . $components['port']) : '')
            . ($components['path'] ?? '')
            . ($components['query'] ? ('?' . $components['query']) : '');
        $this->logger->debug('Wechat OAuth retry built url', $url);

        return $account->getOauth2Url($url);
    }

    protected function logIfRetry()
    {
        $url = $this->getUrl();
        if (isset(parse_url($url)['query']['retry'])) {
            $this->logger->info('微信获取 Code 重试成功', ['url' => $url]);
        }
    }

    protected function getUrl()
    {
        return $this->req['url'] ?: $this->req->getReferer() ?: $this->req->getUrl();
    }
};
