<?php

namespace MiaoxingTest\WechatOa\Service;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;

class WechatOaAccountModelTest extends BaseTestCase
{
    /**
     * @param string $url
     * @param string $scope
     * @param string $state
     * @param string $result
     * @dataProvider providerForGetOauth2Url
     */
    public function testGetOauth2Url(string $url, string $scope, string $state, string $result)
    {
        $account = WechatOaAccountModel::new([
            'applicationId' => 'appId',
            'applicationSecret' => 'appSecret',
            'authScope' => 'snsapi_userinfo',
        ]);
        $oAuth2Url = $account->getOauth2Url($url, $scope, $state);
        $this->assertSame($result, $oAuth2Url);
    }

    public static function providerForGetOauth2Url(): array
    {
        return [
            [
                'https://test.com',
                'snsapi_base',
                '',
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=appId&redirect_uri=https%3A%2F%2Ftest.com&response_type=code&scope=snsapi_base#wechat_redirect',
            ],
            [
                'https://test.com',
                'snsapi_userinfo',
                '',
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=appId&redirect_uri=https%3A%2F%2Ftest.com&response_type=code&scope=snsapi_userinfo#wechat_redirect',
            ],
            [
                'https://t.com',
                '',
                '',
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=appId&redirect_uri=https%3A%2F%2Ft.com&response_type=code&scope=snsapi_userinfo#wechat_redirect',
            ],
            [
                'https://t.com',
                '',
                'state',
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=appId&redirect_uri=https%3A%2F%2Ft.com&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect',
            ],
        ];
    }

    public function testGetApi()
    {
        $account = WechatOaAccountModel::new([
            'applicationId' => 'appId',
            'applicationSecret' => 'appSecret',
        ]);

        $api = $account->getApi();
        $this->assertSame('appId', $api->getAppId());
        $this->assertSame('appSecret', $api->getAppSecret());

        $api2 = $account->getApi();
        $this->assertSame($api, $api2);
    }
}
