## [0.1.1](https://github.com/miaoxing/wechat-oa/compare/v0.1.0...v0.1.1) (2021-10-28)


### Features

* 模型通过 `SnowflakeTrait` 生成 id ([8f7a48e](https://github.com/miaoxing/wechat-oa/commit/8f7a48e1bcdbc4ae38f1984ec64d5ed3c14dc76f))
* **u, wechat-oa:** 增加公众号设置 ([6e2c586](https://github.com/miaoxing/wechat-oa/commit/6e2c5864c6949c9df53fd435dedf5d8586897b8d))
* **wechat-oa:** `WechatOaAccountModel` 增加 `getApi` 和 `getOauth2Url` 方法 ([c083dd9](https://github.com/miaoxing/wechat-oa/commit/c083dd961e99b4ab6ae35bd0ced2ca6b95c0c2a8))
* **wechat-oa:** 增加后端服务号登录接口 ([d28dece](https://github.com/miaoxing/wechat-oa/commit/d28dece23c98ad159e50d827383cf81544bcbc3e))
* **wechat-oa:** 增加微信公众号用户模型 ([a325469](https://github.com/miaoxing/wechat-oa/commit/a325469514e590a9e3f6e6d345bae34dff33db25))
* **wechat-oa:** 增加微信公众号账号模型 ([f6c976a](https://github.com/miaoxing/wechat-oa/commit/f6c976a553a7eae341e31610f8432688af533a52))
* **wechat-oa:** 增加获取服务号登录地址接口 ([3e6509e](https://github.com/miaoxing/wechat-oa/commit/3e6509e42c416ace42574e88ce1824096ab6d67b))
* **wechat-oa:** 授权失败，返回重试地址，最多重试 3 次 ([dc1dcb1](https://github.com/miaoxing/wechat-oa/commit/dc1dcb1b54343148ac9378d589ebc57341a72937))
* **wechat-oa:** 根据配置的授权作用域登录，如果作用域是 `snsapi_userinfo` 则登录后更新用户资料 ([b3d08c9](https://github.com/miaoxing/wechat-oa/commit/b3d08c9777d56c330cc2e66e9df0569d9daff61a))
* **wechat-oa:** 检查微信登录，失效则返回授权地址给前台跳转 ([92d3cab](https://github.com/miaoxing/wechat-oa/commit/92d3cab9409a53a0ebd995e53cbc1669c98d4000))





### Dependencies

* **@mxjs/a-page:** upgrade from `0.2.8` to `0.3.0`
* **@mxjs/a-form:** upgrade from `0.2.15` to `0.3.0`
* **miaoxing:** upgrade from `0.2.5` to `0.3.0`
* **@mxjs/app:** upgrade from `0.3.2` to `0.3.3`
* **@miaoxing/dev:** upgrade from `7.0.1` to `8.0.0`
* **@mxjs/test:** upgrade from `0.1.8` to `0.2.0`
* **@miaoxing/plugin:** upgrade from `0.4.7` to `0.5.0`
* **@miaoxing/user:** upgrade from `0.2.17` to `0.3.0`
* **@miaoxing/wechat:** upgrade to `0.1.0`

# 0.1.0 (2021-09-15)


### Features

* **wechat-oa:** 初始化微信公众号插件 ([1fa6e88](https://github.com/miaoxing/wechat-oa/commit/1fa6e886c4924be877ceba3c5aecaff09c8ba76c))
