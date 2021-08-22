<?php

namespace MiaoxingTest\WechatOa\Pages\MApi\WechatOa\Login;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
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
}
