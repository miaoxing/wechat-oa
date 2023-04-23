<?php

namespace MiaoxingTest\WechatOa\Pages\MApi\WechatOa\Login;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;
use Miaoxing\WechatOa\Service\WechatOaApi;
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
        $wechatApi = $this->getMockBuilder(WechatOaApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->getMock();
        $this->registerMockServices(WechatOaApi::class, $wechatApi);

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

        $ret = Tester::request(['code' => 'test-code', 'url' => 'https://test.com'])->post('/m-api/wechat-oa/login');
        $this->assertRetSuc($ret);
        $this->assertArrayHasKey('token', $ret);
    }

    public function testPostWechatFail()
    {
        $wechatApi = $this->getMockBuilder(WechatOaApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->onlyMethods(['getAccount'])
            ->getMock();
        $this->registerMockServices(WechatOaApi::class, $wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(err('error', 1));

        $wechatApi->expects($this->once())
            ->method('getAccount')
            ->willReturn(WechatOaAccountModel::new([
                'applicationId' => 'x',
                'applicationSecret' => 'y',
            ]));

        $ret = Tester::request(['code' => 'test-code', 'url' => 'https://test.com'])->post('/m-api/wechat-oa/login');
        $this->assertRetErr($ret);

        $this->assertSame('很抱歉，微信授权失败，请返回再试。(error)', $ret['message']);
        $this->assertSame(
            $this->getWechatUrl('x&redirect_uri=https%3A%2F%2Ftest.com%3Fretry%3D1'),
            $ret['retryUrl']
        );
    }

    public function testPostRetryLimit()
    {
        $wechatApi = $this->getMockBuilder(WechatOaApi::class)
            ->addMethods(['getSnsOAuth2AccessToken'])
            ->getMock();
        $this->registerMockServices(WechatOaApi::class, $wechatApi);

        $wechatApi->expects($this->once())
            ->method('getSnsOAuth2AccessToken')
            ->with([
                'code' => 'test-code',
            ])
            ->willReturn(err('error', 1));

        $ret = Tester::request([
            'code' => 'test-code',
            'url' => 'https://test.com?retry=3',
        ])->post('/m-api/wechat-oa/login');
        $this->assertRetErr($ret);

        $this->assertSame('很抱歉，微信授权失败，请返回再试。(error)', $ret['message']);
        $this->assertNull($ret['retryUrl']);
    }

    public function testPostCreateUser()
    {
        $wechatApi = $this->getMockBuilder(WechatOaApi::class)
            ->addMethods(['getSnsOAuth2AccessToken', 'getSnsUserInfo'])
            ->getMock();
        $this->registerMockServices(WechatOaApi::class, $wechatApi);

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
