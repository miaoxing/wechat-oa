import {Page, PageActions} from '@mxjs/a-page';
import {Form, FormAction, FormItem} from '@mxjs/a-form';
import {Radio, Space} from 'antd';
import {Box} from '@fower/react';

const Index = () => {
  return (
    <Page>
      <PageActions mb={12}>
        公众号设置
      </PageActions>
      <Form method="patch" labelCol={{span: 8}} wrapperCol={{span: 8}}>
        <FormItem label="类型" name="type">
          <Radio.Group>
            <Radio value={2}>服务号</Radio>
            <Radio value={1}>订阅号</Radio>
          </Radio.Group>
        </FormItem>
        <FormItem label="AppID（应用ID）" name="applicationId" required/>
        <FormItem label="AppSecret（应用密钥）" name="applicationSecret" required type="password"/>
        <FormItem label="网页授权作用域" name="authScope">
          <Radio.Group style={{marginTop: 5}}>
            <Space direction="vertical">
              <Radio value="snsapi_base">
                静默授权（snsapi_base）
                <Box gray400>不弹出授权页面，直接跳转，只能获取用户 openid</Box>
              </Radio>
              <Radio value="snsapi_userinfo">
                弹出授权页面（snsapi_userinfo）
                <Box gray400>弹出授权页面，可通过 openid 拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 </Box>
              </Radio>
            </Space>
          </Radio.Group>
        </FormItem>
        <FormAction wrapperCol={{offset: 8}} list={false}/>
      </Form>
    </Page>
  );
};

export default Index;
