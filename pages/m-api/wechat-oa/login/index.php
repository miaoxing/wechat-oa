<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\Plugin\Service\User;
use Miaoxing\User\Service\UserModel;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;
use Miaoxing\WechatOa\Service\WechatOaUserModel;
use Wei\Ret;

return new
/**
 * @mixin UrlMixin
 * @mixin LoggerMixin
 */
class extends BaseController {
    protected const MAX_RETRY = 3;
    protected $controllerAuth = false;

    public function get()
    {
        $ret = $this->getAccount();
        if ($ret->isErr()) {
            return $ret;
        }
        $account = $ret['data'];

        $url = $account->getOauth2Url($this->req['url'], 'snsapi_base');

        return suc([
            'url' => $url,
        ]);
    }

    public function post($req)
    {
        $ret = $this->getAccount();
        if ($ret->isErr()) {
            return $ret;
        }
        $account = $ret['data'];

        // 1. code 换取 OpenID
        $api = $account->getApi();
        $ret = $api->getOAuth2AccessTokenByAuth(['code' => $req['code']]);

        if (!isset($ret['openid'])) {
            return err([
                'code' => $ret['code'],
                'message' => ['很抱歉，微信授权失败，请返回再试。(%s)', $ret['message']],
                'retryUrl' => $this->getRetryUrl($account),
            ]);
        }

        // 2. 创建用户并登录
        $oaUser = WechatOaUserModel::findOrInitBy(['openId' => $ret['openid']]);
        if (isset($ret['unionid'])) {
            $oaUser->unionId = $ret['unionid'];
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
        $url = $this->req['url'] ?: $this->req->getReferer() ?: $this->req->getUrl();
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

        if ($retry > static::MAX_RETRY) {
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

        return $account->getOauth2Url($url, 'snsapi_base');
    }
};
