<?php

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaAccountModel $wechatOaAccountModel
 */
class WechatOaAccountModelMixin
{
}

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaAccountModel $wechatOaAccountModel
 */
class WechatOaAccountModelPropMixin
{
}

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaApi $wechatOaApi
 */
class WechatOaApiMixin
{
}

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaApi $wechatOaApi
 */
class WechatOaApiPropMixin
{
}

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaUserModel $wechatOaUserModel
 */
class WechatOaUserModelMixin
{
}

/**
 * @property    Miaoxing\WechatOa\Service\WechatOaUserModel $wechatOaUserModel
 */
class WechatOaUserModelPropMixin
{
}

/**
 * @mixin WechatOaAccountModelMixin
 * @mixin WechatOaApiMixin
 * @mixin WechatOaUserModelMixin
 */
class AutoCompletion
{
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion();
}

/** @var Miaoxing\WechatOa\Service\WechatOaAccountModel $wechatOaAccount */
$wechatOaAccount = wei()->wechatOaAccountModel;

/** @var Miaoxing\WechatOa\Service\WechatOaAccountModel|Miaoxing\WechatOa\Service\WechatOaAccountModel[] $wechatOaAccounts */
$wechatOaAccounts = wei()->wechatOaAccountModel();

/** @var Miaoxing\WechatOa\Service\WechatOaApi $wechatOaApi */
$wechatOaApi = wei()->wechatOaApi;

/** @var Miaoxing\WechatOa\Service\WechatOaUserModel $wechatOaUser */
$wechatOaUser = wei()->wechatOaUserModel;

/** @var Miaoxing\WechatOa\Service\WechatOaUserModel|Miaoxing\WechatOa\Service\WechatOaUserModel[] $wechatOaUsers */
$wechatOaUsers = wei()->wechatOaUserModel();
