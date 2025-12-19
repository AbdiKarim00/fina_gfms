import React from 'react';
import { Card, Typography, Row, Col } from 'antd';

const { Title, Paragraph } = Typography;

export const VehiclesPage: React.FC = () => {
  return (
    <div className="vehicles-page">
      <Title level={2}>Vehicle Management</Title>
      <Paragraph>
        This page will display vehicle management functionality for fleet managers.
      </Paragraph>
      
      <Row gutter={[16, 16]}>
        <Col span={8}>
          <Card title="Total Vehicles" bordered={false}>
            <Title level={3}>125</Title>
            <Paragraph>All vehicles in the fleet</Paragraph>
          </Card>
        </Col>
        <Col span={8}>
          <Card title="Active Vehicles" bordered={false}>
            <Title level={3}>112</Title>
            <Paragraph>Vehicles currently in service</Paragraph>
          </Card>
        </Col>
        <Col span={8}>
          <Card title="Maintenance Due" bordered={false}>
            <Title level={3}>8</Title>
            <Paragraph>Vehicles requiring maintenance</Paragraph>
          </Card>
        </Col>
      </Row>
    </div>
  );
};