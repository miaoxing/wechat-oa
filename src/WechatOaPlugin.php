<?php

namespace Miaoxing\WechatOa;

use Miaoxing\Admin\Service\AdminMenu;
use Miaoxing\Plugin\BasePlugin;
use Miaoxing\Plugin\Service\User;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;

class WechatOaPlugin extends BasePlugin
{
    protected $name = '微信公众号';

    protected $code = 213;

    public function onAdminMenuGetMenus(AdminMenu $menu)
    {
        $menu->child('setting')->addChild()->setLabel('公众号设置')->setUrl('admin/wechat-oa/account');
    }

    public function onCheckAuth()
    {
        if (0 !== strpos($this->req->getRouterPathInfo(), '/m-api/')) {
            return;
        }

        $account = WechatOaAccountModel::findBy('type', WechatOaAccountModel::TYPE_SERVICE);
        if (!$account || !$account->applicationId || !$account->applicationSecret) {
            $this->logger->info('未设置服务号');
            return;
        }

        $ret = User::checkLogin();
        if ($ret->isErr()) {
            // 跨域后台默认获取不到 Referrer，返回 %next% 给前台替换
            $ret['next'] = $account->getOauth2Url('%next%', 'snsapi_base');
            return $ret;
        }
    }
}
