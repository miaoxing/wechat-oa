<?php

namespace Miaoxing\WechatOa\Service;

use Miaoxing\Plugin\BaseService;
use Miaoxing\Wechat\WechatApiTrait;
use Wei\Ret;

/**
 * @method static Ret getSnsOAuth2AccessToken(array $data)
 * @method static Ret getSnsUserInfo(array $data)
 * @method static Ret createTag(array $data)
 * @method static Ret getTags()
 * @method static Ret updateTag(array $data)
 * @method static Ret deleteTag(array $data)
 * @method static Ret getTagUsers(array $data)
 * @method static Ret batchTaggingMembers(array $data)
 * @method static Ret batchUnTaggingMembers(array $data)
 * @method static Ret getTagIdList(array $data)
 * @method static Ret userInfo(array $data)
 * @method static Ret userGet(array $data)
 * @method static Ret createMenu(array $data)
 */
class WechatOaApi extends BaseService
{
    use WechatApiTrait;

    /**
     * @var WechatOaAccountModel
     */
    protected $account;

    protected $configs = [
        'getSnsOAuth2AccessToken' => [
            'url' => 'sns/oauth2/access_token?grant_type=authorization_code',
            'method' => 'GET',
            'accessToken' => false,
            'data' => [
                'appid' => '',
                'secret' => '',
            ],
        ],
        'getSnsUserInfo' => [
            'url' => 'sns/userinfo',
            'accessToken' => false,
        ],
        // @link https://developers.weixin.qq.com/doc/offiaccount/User_Management/User_Tag_Management.html
        'createTag' => 'cgi-bin/tags/create',
        'getTags' => [
            'url' => 'cgi-bin/tags/get',
            'method' => 'GET',
        ],
        'updateTag' => 'cgi-bin/tags/update',
        'deleteTag' => 'cgi-bin/tags/delete',
        'getTagUsers' => 'cgi-bin/user/tag/get',
        'batchTaggingMembers' => 'cgi-bin/tags/members/batchtagging',
        'batchUnTaggingMembers' => 'cgi-bin/tags/members/batchuntagging',
        'getTagIdList' => 'cgi-bin/tags/getidlist',
        'userInfo' => [
            'url' => 'cgi-bin/user/info',
            'method' => 'GET',
        ],
        'userGet' => [
            'url' => 'cgi-bin/user/get',
            'method' => 'GET',
        ],
        // @link https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html
        'createMenu' => 'cgi-bin/menu/create',
    ];

    public function getAccount(): WechatOaAccountModel
    {
        if (!$this->account) {
            $this->account = WechatOaAccountModel::findOrInitBy();
        }
        return $this->account;
    }

    protected function loadApp()
    {
        $account = $this->getAccount();
        $this->appId = $account->applicationId;
        $this->appSecret = $account->applicationSecret;
    }
}
