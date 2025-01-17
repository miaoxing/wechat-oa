<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Wechat\Service\WechatApi;

/**
 * @mixin \ReqMixin
 * @property string|null $id
 * @property string $appId 应用编号
 * @property int $type 账号类型。1:订阅号;2:服务号
 * @property string $sourceId 微信原始ID
 * @property string $nickName 昵称
 * @property string $headImg 头像
 * @property string $applicationId 应用ID
 * @property string $applicationSecret 应用密钥
 * @property string $authScope 授权作用域。snsapi_base；snsapi_userinfo
 * @property string $token 消息签名参数
 * @property string $encodingAesKey 消息加密密钥
 * @property int $dataType 数据格式。1:JSON；2:XML
 * @property bool $isVerified 是否认证
 * @property bool $isAuthed 是否已通过第三方平台授权
 * @property string $refreshToken 授权方的刷新令牌
 * @property string $verifyTicket component_verify_ticket
 * @property string $funcInfo 授权给开发者的权限集列表
 * @property string $businessInfo 功能的开通状况
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 */
class WechatOaAccountModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;

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
    public function getOauth2Url(?string $url = null, ?string $scope = null, ?string $state = null): string
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
