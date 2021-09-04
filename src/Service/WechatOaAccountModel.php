<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Wechat\Service\WechatApi;
use Miaoxing\WechatOa\Metadata\WechatOaAccountTrait;

/**
 * @mixin \ReqMixin
 */
class WechatOaAccountModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;
    use WechatOaAccountTrait;

    public const TYPE_SUBSCRIPTION = 1;

    public const TYPE_SERVICE = 2;

    /**
     * @var WechatApi
     */
    protected $api;

    /**
     * 获取当前账号的微信 API 服务
     *
     * @return WechatApi
     */
    public function getApi(): WechatApi
    {
        if (!$this->api) {
            $this->api = new WechatApi([
                'wei' => $this->wei,
                'appId' => $this->applicationId,
                'appSecret' => $this->applicationSecret,
            ]);
        }
        return $this->api;
    }

    /**
     * 生成网页授权地址
     *
     * @param string|null $url
     * @param string|null $scope
     * @param string|null $state
     * @return string
     */
    public function getOauth2Url(string $url = null, string $scope = null, string $state = null): string
    {
        $url || $url = $this->req->getUrl();
        $scope || $scope = $this->authScope ?: 'snsapi_base';

        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?'
            . http_build_query(array_filter([
                'appid' => $this->applicationId,
                'redirect_uri' => $url,
                'response_type' => 'code',
                'scope' => $scope,
                'state' => $state,
                'component_appid' => $this->isAuthed ? wei()->wechatComponentApi->getAppId() : null,
            ]));

        return $url . '#wechat_redirect';
    }
}
