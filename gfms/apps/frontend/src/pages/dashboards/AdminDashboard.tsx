import React from 'react';
import { Card, Row, Col, Statistic, Typography, Space, Alert } from 'antd';
import {
  CarOutlined,
  FileTextOutlined,
  ToolOutlined,
  TeamOutlined,
} from '@ant-design/icons';

const { Title, Text } = Typography;

export const AdminDashboard: React.FC = () => {
  return (
    <div style={{ padding: '24px' }}>
      <Space vertical size="large" style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ margin: 0 }}>
            Admin Dashboard
          </Title>
          <Text type="secondary">Organization management and oversight</Text>
        </div>

        <Alert
          title="Admin Access"
          description="Manage vehicles, users, bookings, and maintenance for your organization."
          type="success"
          showIcon
        />

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Total Vehicles"
                value={6}
                prefix={<CarOutlined style={{ color: '#006600' }} />}
                styles={{ content: { color: '#006600' } }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Active Bookings"
                value={0}
                prefix={<FileTextOutlined style={{ color: '#1890ff' }} />}
                styles={{ content: { color: '#1890ff' } }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Maintenance Due"
                value={1}
                prefix={<ToolOutlined style={{ color: '#faad14' }} />}
                styles={{ content: { color: '#faad14' } }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Users"
                value={5}
                prefix={<TeamOutlined style={{ color: '#722ed1' }} />}
                styles={{ content: { color: '#722ed1' } }}
              />
            </Card>
          </Col>
        </Row>

        <Card title="Recent Activity">
          <Text>Activity logs and recent changes coming soon...</Text>
        </Card>
      </Space>
    </div>
  );
};
