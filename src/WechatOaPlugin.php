<?php

namespace Miaoxing\WechatOa;

use Miaoxing\Plugin\BasePlugin;

class WechatOaPlugin extends BasePlugin
{
    protected $name = '微信公众号';

    protected $code = 213;

    public function onAdminNavGetNavs(&$navs, &$categories, &$subCategories)
    {
        $subCategories[] = [
            'parentId' => 'setting',
            'url' => 'admin/wechat-oa/account',
            'name' => '公众号设置',
        ];
    }
}
