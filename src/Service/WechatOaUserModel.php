<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\User\Model\BelongsToUserTrait;
use Miaoxing\WechatOa\Metadata\WechatOaUserTrait;

class WechatOaUserModel extends BaseModel
{
    use BelongsToUserTrait;
    use HasAppIdTrait;
    use ModelTrait;
    use WechatOaUserTrait;

    protected $columns = [
        'privilege' => [
            'cast' => 'list',
        ],
    ];
}
