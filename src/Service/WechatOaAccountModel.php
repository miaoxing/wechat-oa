<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\WechatOa\Metadata\WechatOaAccountTrait;

class WechatOaAccountModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use WechatOaAccountTrait;
}
