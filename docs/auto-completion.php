<?php

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaUserModel $wechatOaUserModel
 * @method      Miaoxing\WechatOa\Service\WechatOaUserModel wechatOaUserModel() 返回当前对象
 */
class WechatOaUserModelMixin {
}

/**
 * @mixin WechatOaUserModelMixin
 */
class AutoCompletion {
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion;
}

/** @var Miaoxing\WechatOa\Service\WechatOaUserModel $wechatOaUser */
$wechatOaUser = wei()->wechatOaUserModel;

/** @var Miaoxing\WechatOa\Service\WechatOaUserModel|Miaoxing\WechatOa\Service\WechatOaUserModel[] $wechatOaUsers */
$wechatOaUsers = wei()->wechatOaUserModel();
