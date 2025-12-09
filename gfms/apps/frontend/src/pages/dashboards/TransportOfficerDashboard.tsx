import React from 'react';
import { Card, Row, Col, Statistic, Typography, Space, Alert, Button } from 'antd';
import {
  CarOutlined,
  FileTextOutlined,
  PlusOutlined,
  ClockCircleOutlined,
} from '@ant-design/icons';

const { Title, Text } = Typography;

export const TransportOfficerDashboard: React.FC = () => {
  return (
    <div style={{ padding: '24px' }}>
      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ margin: 0 }}>
            Transport Officer Dashboard
          </Title>
          <Text type="secondary">Manage your vehicle bookings and requests</Text>
        </div>

        <Alert
          message="Transport Officer Access"
          description="Book vehicles, view available fleet, and manage your transportation requests."
          type="info"
          showIcon
        />

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="My Bookings"
                value={0}
                prefix={<FileTextOutlined style={{ color: '#1890ff' }} />}
                valueStyle={{ color: '#1890ff' }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Pending Approvals"
                value={0}
                prefix={<ClockCircleOutlined style={{ color: '#faad14' }} />}
                valueStyle={{ color: '#faad14' }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Available Vehicles"
                value={4}
                prefix={<CarOutlined style={{ color: '#52c41a' }} />}
                valueStyle={{ color: '#52c41a' }}
              />
            </Card>
          </Col>
        </Row>

        <Card
          title="Quick Actions"
          extra={
            <Button type="primary" icon={<PlusOutlined />}>
              New Booking
            </Button>
          }
        >
          <Text>Booking system coming soon...</Text>
        </Card>
      </Space>
    </div>
  );
};
