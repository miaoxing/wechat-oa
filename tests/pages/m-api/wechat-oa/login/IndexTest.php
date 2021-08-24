<?php

namespace MiaoxingTest\WechatOa\Pages\MApi\WechatOa\Login;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Wechat\Service\WechatApi;
use Miaoxing\WechatOa\Service\WechatOaAccountModel;

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
        $this->assertSame('https://open.weixin.qq.com/connect/oauth2/authorize?appid=x&redirect_uri=https%3A%2F%2Ftest.com&response_type=code&scope=snsapi_base#wechat_redirect', $ret['url']);
    }

    public function testPost()
    {
        $wechatApi = $this->getServiceMock(WechatApi::class, [
            'getOAuth2AccessTokenByAuth',
        ]);

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getOAuth2AccessTokenByAuth')
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
        $wechatApi = $this->getServiceMock(WechatApi::class, [
            'getOAuth2AccessTokenByAuth',
        ]);

        $account = $this->getModelServiceMock(WechatOaAccountModel::class, [
            'findBy',
            'getApi',
        ]);

        $account->setOption('table', 'wechat_oa_accounts');

        $account->expects($this->once())
            ->method('getApi')
            ->willReturn($wechatApi);

        $wechatApi->expects($this->once())
            ->method('getOAuth2AccessTokenByAuth')
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

        $this->assertSame('很抱歉，微信授权失败，请返回再试。(error)', $ret['message']);
        $this->assertSame('https://open.weixin.qq.com/connect/oauth2/authorize?appid=x&redirect_uri=https%3A%2F%2Ftest.com%3Fretry%3D1&response_type=code&scope=snsapi_base#wechat_redirect', $ret['retryUrl']);
    }
}
