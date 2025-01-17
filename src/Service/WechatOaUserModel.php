<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\User\Model\BelongsToUserTrait;

/**
 * @property string|null $id
 * @property string $appId 应用编号
 * @property string $userId 用户编号
 * @property string $openId 微信用户 OpenID
 * @property string $unionId 微信用户 UnionID
 * @property bool $subscribe 是否已订阅
 * @property string $nickName 昵称
 * @property int $sex 性别。0:未知;1:男;2:女
 * @property string $language 语言
 * @property string $city 省份
 * @property string $province 城市
 * @property string $country 国家
 * @property array $privilege 特权信息
 * @property string $headImgUrl 头像地址
 * @property string|null $subscribeTime 用户关注时间
 * @property string $remark 对粉丝的备注
 * @property int $groupId 所在的分组ID
 * @property string $tagIdList 被打上的标签ID列表
 * @property string $subscribeScene 关注的渠道来源
 * @property string $qrScene 二维码扫码场景
 * @property string $qrSceneStr 二维码扫码场景描述
 * @property string|null $updatedInfoAt 最后更新信息时间
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 */
class WechatOaUserModel extends BaseModel
{
    use BelongsToUserTrait;
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;

    protected $columns = [
        'privilege' => [
            'cast' => 'list',
        ],
    ];
}
