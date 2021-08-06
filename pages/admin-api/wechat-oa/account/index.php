<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;

return new class extends BaseController {
    public function get()
    {
        return $this->getAccount()->toRet();
    }

    public function patch($req)
    {
        $account = $this->getAccount();

        $account->save($req);

        return $account->toRet();
    }

    protected function getAccount()
    {
        return WechatOaAccountModel::findOrInitBy([]);
    }
};
