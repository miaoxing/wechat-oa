<?php

namespace MiaoxingTest\WechatOa\Pages\MApi\WechatOa\Login;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Wechat\Service\WechatApi;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;
use Miaoxing\WechatOa\Service\WechatOaUserModel;

class IndexTest extends BaseTestCase
{
    public function testGet()
    {
        $accountService = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
        ]);

        $account = WechatOaAccountModel::new([
            'applicationId' => 'x',
            'applicationSecret' => 'y',
        ]);

        $accountService->expects($this->once())
            ->method('findBy')
            ->willReturn($account);

        $ret = Tester::request(['url' => 'https://test.com'])->get('/m-api/wechat-oa/login');

        $this->assertRetSuc($ret);
        $this->assertSame(
            $this->getWechatUrl('x&redirect_uri=https%3A%2F%2Ftest.com'),
            $ret['url']
        );
    }

    public function testPost()
    {
        $wechatApi = $this->getMockBuilder(WechatApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->getMock();

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(suc([
                'scope' => 'snsapi_base',
                'openid' => 'test-openid',
                'unionid' => 'test-unionid',
            ]));

        $account->fromArray([
            'applicationId' => 'x',
            'applicationSecret' => 'y',
        ]);

        $account->expects($this->once())
            ->method('findBy')
            ->willReturn($account);

        $ret = Tester::request(['code' => 'test-code', 'url' => 'https://test.com'])->post('/m-api/wechat-oa/login');
        $this->assertRetSuc($ret);
        $this->assertArrayHasKey('token', $ret);
    }

    public function testPostWechatFail()
    {
        $wechatApi = $this->getMockBuilder(WechatApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->getMock();

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(err('error', 1));

        $account->fromArray([
            'applicationId' => 'x',
            'applicationSecret' => 'y',
        ]);

        $account->expects($this->once())
            ->method('findBy')
            ->willReturn($account);

        $ret = Tester::request(['code' => 'test-code', 'url' => 'https://test.com'])->post('/m-api/wechat-oa/login');
        $this->assertRetErr($ret);

        $this->assertSame('???????????????????????????????????????????????????(error)', $ret['message']);
        $this->assertSame(
            $this->getWechatUrl('x&redirect_uri=https%3A%2F%2Ftest.com%3Fretry%3D1'),
            $ret['retryUrl']
        );
    }

    public function testPostRetryLimit()
    {
        $wechatApi = $this->getMockBuilder(WechatApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->getMock();

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(err('error', 1));

        $account->fromArray([
            'applicationId' => 'x',
            'applicationSecret' => 'y',
        ]);

        $account->expects($this->once())
            ->method('findBy')
            ->willReturn($account);

        $ret = Tester::request([
            'code' => 'test-code',
            'url' => 'https://test.com?retry=3',
        ])->post('/m-api/wechat-oa/login');
        $this->assertRetErr($ret);

        $this->assertSame('???????????????????????????????????????????????????(error)', $ret['message']);
        $this->assertNull($ret['retryUrl']);
    }

    public function testPostCreateUser()
    {
        $wechatApi = $this->getMockBuilder(WechatApi::class)
            ->addMethods(['getSnsOAuth2AccessToken', 'getSnsUserInfo'])
            ->getMock();

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(suc([
                'scope' => 'snsapi_userinfo',
                'access_token' => 'access_token',
                'openid' => 'test-openid',
                'unionid' => 'test-unionid',
            ]));

        $wechatApi->expects($this->once())
            ->method('getSnsUserInfo')
            ->willReturn(suc([
                'nickname' => 'nickname',
                'sex' => '1',
                'language' => 'language',
                'city' => 'city',
                'province' => 'province',
                'country' => 'country',
                'headimgurl' => 'headimgurl',
                'privilege' => ['privilege1', 'privilege2'],
            ]));

        $account->fromArray([
            'applicationId' => 'x',
            'applicationSecret' => 'y',
            'authScope' => 'snsapi_userinfo',
        ]);

        $account->expects($this->once())
            ->method('findBy')
            ->willReturn($account);

        $ret = Tester::request(['code' => 'test-code', 'url' => 'https://test.com'])->post('/m-api/wechat-oa/login');
        $this->assertRetSuc($ret);
        $this->assertArrayHasKey('token', $ret);

        $oaUser = WechatOaUserModel::findBy('openId', 'test-openid');
        $this->assertSame([
            'nickName' => 'nickname',
            'sex' => 1,
            'language' => 'language',
            'city' => 'city',
            'province' => 'province',
            'country' => 'country',
            'privilege' => ['privilege1', 'privilege2'],
            'headImgUrl' => 'headimgurl',
        ], $oaUser->toArray(['nickName', 'sex', 'language', 'city', 'province', 'country', 'privilege', 'headImgUrl']));
    }

    protected function getWechatUrl(string $url): string
    {
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
            . $url
            . '&response_type=code&scope=snsapi_base#wechat_redirect';
    }
}
