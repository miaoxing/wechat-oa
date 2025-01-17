<?php

namespace Miaoxing\WechatOa\Migration;

use Wei\Migration\BaseMigration;

class V20210811102856CreateWechatOaUsersTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('wechat_oa_users')
            ->bigId()
            ->uBigInt('app_id')->comment('应用编号')
            ->uBigInt('user_id')->comment('用户编号')
            ->string('open_id', 32)->comment('微信用户 OpenID')
            ->string('union_id', 32)->comment('微信用户 UnionID')
            ->bool('subscribe')->comment('是否已订阅')
            ->string('nick_name')->comment('昵称')
            ->uTinyInt('sex')->comment('性别。0:未知;1:男;2:女')
            ->string('language', 8)->comment('语言')
            ->string('city', 32)->comment('省份')
            ->string('province', 32)->comment('城市')
            ->string('country', 32)->comment('国家')
            ->string('privilege', 64)->comment('特权信息')
            ->string('head_img_url')->comment('头像地址')
            ->timestamp('subscribe_time')->comment('用户关注时间')
            ->string('remark', 32)->comment('对粉丝的备注')
            ->uInt('group_id')->comment('所在的分组ID')
            ->string('tag_id_list', 64)->comment('被打上的标签ID列表')
            ->string('subscribe_scene', 32)->comment('关注的渠道来源')
            ->string('qr_scene', 32)->comment('二维码扫码场景')
            ->string('qr_scene_str', 32)->comment('二维码扫码场景描述')
            ->timestamp('updated_info_at')->comment('最后更新信息时间')
            ->timestamps()
            ->userstamps()
            ->index('user_id')
            ->index('open_id')
            ->index('union_id')
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('wechat_oa_users');
    }
}
