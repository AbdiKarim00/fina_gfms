import React from 'react';
import { Card, Row, Col, Statistic, Button, Space, Typography } from 'antd';
import { CarOutlined, UserOutlined, FileTextOutlined, PlusOutlined } from '@ant-design/icons';
import { useAuth } from '../contexts/AuthContext';

const { Title, Text } = Typography;

export const DashboardPageV2: React.FC = () => {
  const { user } = useAuth();

  return (
    <div style={{ padding: '24px' }}>
      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        {/* Header */}
        <div>
          <Title level={2} style={{ margin: 0 }}>Dashboard</Title>
          <Text type="secondary">Welcome back, {user?.name}!</Text>
        </div>

        {/* Stats Cards */}
        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Total Vehicles"
                value={2}
                prefix={<CarOutlined style={{ color: '#006600' }} />}
                valueStyle={{ color: '#006600' }}
              />
            </Card>
          </Col>
          
          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Active Drivers"
                value={1}
                prefix={<UserOutlined style={{ color: '#0D6EFD' }} />}
                valueStyle={{ color: '#0D6EFD' }}
              />
            </Card>
          </Col>
          
          <Col xs={24} sm={12} lg={8}>
            <Card hoverable>
              <Statistic
                title="Pending Bookings"
                value={0}
                prefix={<FileTextOutlined style={{ color: '#FFC107' }} />}
                valueStyle={{ color: '#FFC107' }}
              />
            </Card>
          </Col>
        </Row>

        {/* Quick Actions */}
        <Card title="Quick Actions">
          <Row gutter={[16, 16]}>
            <Col xs={24} sm={12} lg={6}>
              <Button 
                type="primary" 
                block 
                size="large"
                icon={<PlusOutlined />}
              >
                Add Vehicle
              </Button>
            </Col>
            <Col xs={24} sm={12} lg={6}>
              <Button 
                block 
                size="large"
                icon={<PlusOutlined />}
              >
                Book Vehicle
              </Button>
            </Col>
            <Col xs={24} sm={12} lg={6}>
              <Button 
                block 
                size="large"
                icon={<PlusOutlined />}
              >
                Add Driver
              </Button>
            </Col>
            <Col xs={24} sm={12} lg={6}>
              <Button 
                block 
                size="large"
                icon={<FileTextOutlined />}
              >
                View Reports
              </Button>
            </Col>
          </Row>
        </Card>
      </Space>
    </div>
  );
};
