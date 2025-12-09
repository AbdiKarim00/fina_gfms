import React from 'react';
import { Card, Row, Col, Statistic, Typography, Space, Alert } from 'antd';
import {
  TeamOutlined,
  CarOutlined,
  AuditOutlined,
  SafetyOutlined,
} from '@ant-design/icons';

const { Title, Text } = Typography;

export const SuperAdminDashboard: React.FC = () => {
  return (
    <div style={{ padding: '24px' }}>
      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ margin: 0 }}>
            Super Admin Dashboard
          </Title>
          <Text type="secondary">System-wide overview and management</Text>
        </div>

        <Alert
          message="Super Admin Access"
          description="You have full system access. Use this dashboard to manage users, organizations, roles, and system settings."
          type="info"
          showIcon
        />

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Total Users"
                value={5}
                prefix={<TeamOutlined style={{ color: '#1890ff' }} />}
                valueStyle={{ color: '#1890ff' }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Organizations"
                value={3}
                prefix={<AuditOutlined style={{ color: '#722ed1' }} />}
                valueStyle={{ color: '#722ed1' }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Total Vehicles"
                value={6}
                prefix={<CarOutlined style={{ color: '#006600' }} />}
                valueStyle={{ color: '#006600' }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable>
              <Statistic
                title="Roles"
                value={5}
                prefix={<SafetyOutlined style={{ color: '#fa8c16' }} />}
                valueStyle={{ color: '#fa8c16' }}
              />
            </Card>
          </Col>
        </Row>

        <Card title="Quick Actions">
          <Text>System management features coming soon...</Text>
        </Card>
      </Space>
    </div>
  );
};
