<?php

namespace Miaoxing\WechatOa\Metadata;

/**
 * @property int|null $id
 * @property int $appId 应用编号
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
 * @property int $createdBy
 * @property int $updatedBy
 * @internal will change in the future
 */
trait WechatOaAccountTrait
{
}
