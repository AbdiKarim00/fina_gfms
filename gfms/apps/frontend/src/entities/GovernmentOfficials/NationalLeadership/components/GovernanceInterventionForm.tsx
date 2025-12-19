import React from 'react';
import { Card, Form, Input, Button, Select, Typography, message } from 'antd';
import { cabinetSecretaryService } from '../services/CabinetSecretaryService';

const { Title, Paragraph } = Typography;
const { Option } = Select;
const { TextArea } = Input;

export const GovernanceInterventionForm: React.FC = () => {
  const [form] = Form.useForm();
  const [loading, setLoading] = React.useState(false);

  const onFinish = async (values: any) => {
    setLoading(true);
    try {
      await cabinetSecretaryService.postIntervention(values);
      message.success('Governance intervention recorded successfully');
      form.resetFields();
    } catch (error) {
      message.error('Failed to record intervention');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card title={<Title level={4}>Emergency Governance Intervention</Title>}>
      <Paragraph type="secondary">
        Authorize emergency actions or intervene in active system workflows. All interventions are audited.
      </Paragraph>
      <Form form={form} layout="vertical" onFinish={onFinish}>
        <Form.Item
          name="workflow_id"
          label="Workflow / Transaction ID"
          rules={[{ required: true, message: 'Please input the workflow ID' }]}
        >
          <Input placeholder="e.g. WF-2025-001" />
        </Form.Item>

        <Form.Item
          name="action"
          label="Intervention Action"
          rules={[{ required: true, message: 'Please select an action' }]}
        >
          <Select placeholder="Select action">
            <Option value="suspend">Suspend Workflow</Option>
            <Option value="override">Override Approval</Option>
            <Option value="redirect">Redirect Task</Option>
            <Option value="audit">Trigger Immediate Audit</Option>
          </Select>
        </Form.Item>

        <Form.Item
          name="reason"
          label="Justification"
          rules={[{ required: true, message: 'Please provide a reason' }]}
        >
          <TextArea rows={4} placeholder="Detailed justification for intervention" />
        </Form.Item>

        <Form.Item>
          <Button type="primary" danger htmlType="submit" loading={loading} block>
            Authorize Intervention
          </Button>
        </Form.Item>
      </Form>
    </Card>
  );
};
