import {Page, PageActions} from '@mxjs/a-page';
import {Form, FormAction, FormItem} from '@mxjs/a-form';
import {Radio} from 'antd';

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
        <FormAction wrapperCol={{offset: 8}} list={false}/>
      </Form>
    </Page>
  );
};

export default Index;
