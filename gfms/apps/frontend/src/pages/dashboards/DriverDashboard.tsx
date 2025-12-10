import React from 'react';
import { Card, Row, Col, Statistic, Typography, Space, Alert, Timeline } from 'antd';
import {
  CarOutlined,
  DashboardOutlined,
  FileTextOutlined,
  CheckCircleOutlined,
} from '@ant-design/icons';

const { Title, Text } = Typography;

export const DriverDashboard: React.FC = () => {
  return (
    <div style={{ padding: '24px' }}>
      <Space vertical size="large" style={{ width: '100%' }}>
        <div>
          <Title level={2} style={{ margin: 0 }}>
            Driver Dashboard
          </Title>
          <Text type="secondary">Your assignments and trip logs</Text>
        </div>

        <Alert
          title="Driver Access"
          description="View your vehicle assignments, log trips, and record fuel consumption."
          type="success"
          showIcon
        />

        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Current Assignment"
                value="None"
                prefix={<CarOutlined style={{ color: '#006600' }} />}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Trips This Month"
                value={0}
                prefix={<FileTextOutlined style={{ color: '#1890ff' }} />}
                styles={{ content: { color: '#1890ff' } }}
              />
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Total Distance"
                value={0}
                suffix="km"
                prefix={<DashboardOutlined style={{ color: '#722ed1' }} />}
                styles={{ content: { color: '#722ed1' } }}
              />
            </Card>
          </Col>
        </Row>

        <Row gutter={[16, 16]}>
          <Col xs={24} lg={12}>
            <Card title="Today's Schedule">
              <Text type="secondary">No assignments for today</Text>
            </Card>
          </Col>

          <Col xs={24} lg={12}>
            <Card title="Recent Trips">
              <Timeline
                items={[
                  {
                    dot: <CheckCircleOutlined style={{ color: '#52c41a' }} />,
                    children: 'No trips recorded yet',
                  },
                ]}
              />
            </Card>
          </Col>
        </Row>
      </Space>
    </div>
  );
};
