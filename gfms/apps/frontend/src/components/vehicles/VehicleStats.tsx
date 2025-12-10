import React from 'react';
import { Card, Row, Col, Statistic, Progress, Space, Typography } from 'antd';
import {
  CarOutlined,
  CheckCircleOutlined,
  ToolOutlined,
  StopOutlined,
  DashboardOutlined,
  ThunderboltOutlined,
} from '@ant-design/icons';
import { Vehicle } from '../../types';

const { Text } = Typography;

interface VehicleStatsProps {
  vehicles: Vehicle[];
}

export const VehicleStats: React.FC<VehicleStatsProps> = ({ vehicles }) => {
  // Calculate statistics
  const totalVehicles = vehicles.length;
  const activeVehicles = vehicles.filter((v) => v.status === 'active').length;
  const maintenanceVehicles = vehicles.filter((v) => v.status === 'maintenance').length;
  const inactiveVehicles = vehicles.filter((v) => v.status === 'inactive').length;

  // Calculate percentages
  const activePercentage = totalVehicles > 0 ? (activeVehicles / totalVehicles) * 100 : 0;
  const maintenancePercentage = totalVehicles > 0 ? (maintenanceVehicles / totalVehicles) * 100 : 0;
  const inactivePercentage = totalVehicles > 0 ? (inactiveVehicles / totalVehicles) * 100 : 0;

  // Fuel type distribution
  const fuelTypes = vehicles.reduce((acc, v) => {
    acc[v.fuel_type] = (acc[v.fuel_type] || 0) + 1;
    return acc;
  }, {} as Record<string, number>);

  const dieselCount = fuelTypes.diesel || 0;
  const petrolCount = fuelTypes.petrol || 0;
  const electricCount = fuelTypes.electric || 0;
  const hybridCount = fuelTypes.hybrid || 0;

  // Average mileage
  const vehiclesWithMileage = vehicles.filter((v) => v.mileage && v.mileage > 0);
  const avgMileage =
    vehiclesWithMileage.length > 0
      ? vehiclesWithMileage.reduce((sum, v) => sum + (v.mileage || 0), 0) / vehiclesWithMileage.length
      : 0;

  // Vehicles with log books
  const withLogBook = vehicles.filter((v) => v.has_log_book).length;
  const logBookPercentage = totalVehicles > 0 ? (withLogBook / totalVehicles) * 100 : 0;

  return (
    <Row gutter={[16, 16]}>
      {/* Total Vehicles Card */}
      <Col xs={24} sm={12} lg={6}>
        <Card
          hoverable
          style={{
            borderRadius: '12px',
            background: 'linear-gradient(135deg, #006600 0%, #008800 100%)',
            color: 'white',
          }}
        >
          <Space vertical size="small" style={{ width: '100%' }}>
            <CarOutlined style={{ fontSize: '32px', color: 'white' }} />
            <Statistic
              title={<Text style={{ color: 'rgba(255,255,255,0.85)' }}>Total Vehicles</Text>}
              value={totalVehicles}
              styles={{ content: { color: 'white', fontSize: '32px', fontWeight: 'bold' } }}
            />
            <Text style={{ color: 'rgba(255,255,255,0.75)', fontSize: '12px' }}>
              Fleet Size
            </Text>
          </Space>
        </Card>
      </Col>

      {/* Active Vehicles Card with Progress */}
      <Col xs={24} sm={12} lg={6}>
        <Card hoverable style={{ borderRadius: '12px', height: '100%' }}>
          <Space vertical size="small" style={{ width: '100%' }}>
            <Space>
              <CheckCircleOutlined style={{ fontSize: '24px', color: '#52c41a' }} />
              <Text strong style={{ fontSize: '16px' }}>
                Active Vehicles
              </Text>
            </Space>
            <Statistic
              value={activeVehicles}
              styles={{ content: { color: '#52c41a', fontSize: '28px', fontWeight: 'bold' } }}
            />
            <div>
              <Progress
                percent={Math.round(activePercentage)}
                strokeColor={{
                  '0%': '#52c41a',
                  '100%': '#73d13d',
                }}
                status="active"
                showInfo={false}
              />
              <Text type="secondary" style={{ fontSize: '12px' }}>
                {activePercentage.toFixed(1)}% of fleet operational
              </Text>
            </div>
          </Space>
        </Card>
      </Col>

      {/* Maintenance Vehicles Card with Progress */}
      <Col xs={24} sm={12} lg={6}>
        <Card hoverable style={{ borderRadius: '12px', height: '100%' }}>
          <Space vertical size="small" style={{ width: '100%' }}>
            <Space>
              <ToolOutlined style={{ fontSize: '24px', color: '#faad14' }} />
              <Text strong style={{ fontSize: '16px' }}>
                In Maintenance
              </Text>
            </Space>
            <Statistic
              value={maintenanceVehicles}
              styles={{ content: { color: '#faad14', fontSize: '28px', fontWeight: 'bold' } }}
            />
            <div>
              <Progress
                percent={Math.round(maintenancePercentage)}
                strokeColor={{
                  '0%': '#faad14',
                  '100%': '#ffc53d',
                }}
                showInfo={false}
              />
              <Text type="secondary" style={{ fontSize: '12px' }}>
                {maintenancePercentage.toFixed(1)}% under service
              </Text>
            </div>
          </Space>
        </Card>
      </Col>

      {/* Inactive Vehicles Card with Progress */}
      <Col xs={24} sm={12} lg={6}>
        <Card hoverable style={{ borderRadius: '12px', height: '100%' }}>
          <Space vertical size="small" style={{ width: '100%' }}>
            <Space>
              <StopOutlined style={{ fontSize: '24px', color: '#8c8c8c' }} />
              <Text strong style={{ fontSize: '16px' }}>
                Inactive
              </Text>
            </Space>
            <Statistic
              value={inactiveVehicles}
              styles={{ content: { color: '#8c8c8c', fontSize: '28px', fontWeight: 'bold' } }}
            />
            <div>
              <Progress
                percent={Math.round(inactivePercentage)}
                strokeColor="#8c8c8c"
                showInfo={false}
              />
              <Text type="secondary" style={{ fontSize: '12px' }}>
                {inactivePercentage.toFixed(1)}% not in use
              </Text>
            </div>
          </Space>
        </Card>
      </Col>

      {/* Fuel Type Distribution Card */}
      <Col xs={24} sm={12} lg={8}>
        <Card
          hoverable
          title={
            <Space>
              <ThunderboltOutlined style={{ color: '#1890ff' }} />
              <span>Fuel Type Distribution</span>
            </Space>
          }
          style={{ borderRadius: '12px', height: '100%' }}
        >
          <Space vertical size="middle" style={{ width: '100%' }}>
            <div>
              <Space style={{ width: '100%', justifyContent: 'space-between' }}>
                <Text>Diesel</Text>
                <Text strong>{dieselCount}</Text>
              </Space>
              <Progress
                percent={totalVehicles > 0 ? (dieselCount / totalVehicles) * 100 : 0}
                strokeColor="#1890ff"
                showInfo={false}
                size="small"
              />
            </div>
            <div>
              <Space style={{ width: '100%', justifyContent: 'space-between' }}>
                <Text>Petrol</Text>
                <Text strong>{petrolCount}</Text>
              </Space>
              <Progress
                percent={totalVehicles > 0 ? (petrolCount / totalVehicles) * 100 : 0}
                strokeColor="#722ed1"
                showInfo={false}
                size="small"
              />
            </div>
            <div>
              <Space style={{ width: '100%', justifyContent: 'space-between' }}>
                <Text>Electric</Text>
                <Text strong>{electricCount}</Text>
              </Space>
              <Progress
                percent={totalVehicles > 0 ? (electricCount / totalVehicles) * 100 : 0}
                strokeColor="#52c41a"
                showInfo={false}
                size="small"
              />
            </div>
            <div>
              <Space style={{ width: '100%', justifyContent: 'space-between' }}>
                <Text>Hybrid</Text>
                <Text strong>{hybridCount}</Text>
              </Space>
              <Progress
                percent={totalVehicles > 0 ? (hybridCount / totalVehicles) * 100 : 0}
                strokeColor="#13c2c2"
                showInfo={false}
                size="small"
              />
            </div>
          </Space>
        </Card>
      </Col>

      {/* Average Mileage Card */}
      <Col xs={24} sm={12} lg={8}>
        <Card
          hoverable
          title={
            <Space>
              <DashboardOutlined style={{ color: '#722ed1' }} />
              <span>Average Mileage</span>
            </Space>
          }
          style={{ borderRadius: '12px', height: '100%' }}
        >
          <Space vertical size="middle" style={{ width: '100%' }}>
            <Statistic
              value={avgMileage.toFixed(0)}
              suffix="km"
              styles={{ content: { color: '#722ed1', fontSize: '32px', fontWeight: 'bold' } }}
            />
            <div>
              <Text type="secondary" style={{ fontSize: '12px' }}>
                Across {vehiclesWithMileage.length} vehicles with recorded mileage
              </Text>
            </div>
            <Progress
              percent={Math.min((avgMileage / 200000) * 100, 100)}
              strokeColor={{
                '0%': '#722ed1',
                '50%': '#9254de',
                '100%': '#b37feb',
              }}
              showInfo={false}
            />
            <Text type="secondary" style={{ fontSize: '11px' }}>
              Target: 200,000 km lifecycle
            </Text>
          </Space>
        </Card>
      </Col>

      {/* Log Book Compliance Card */}
      <Col xs={24} sm={12} lg={8}>
        <Card
          hoverable
          title={
            <Space>
              <CheckCircleOutlined style={{ color: '#13c2c2' }} />
              <span>Log Book Compliance</span>
            </Space>
          }
          style={{ borderRadius: '12px', height: '100%' }}
        >
          <Space vertical size="middle" style={{ width: '100%' }}>
            <Statistic
              value={withLogBook}
              suffix={`/ ${totalVehicles}`}
              styles={{ content: { color: '#13c2c2', fontSize: '32px', fontWeight: 'bold' } }}
            />
            <div>
              <Progress
                type="circle"
                percent={Math.round(logBookPercentage)}
                strokeColor={{
                  '0%': '#13c2c2',
                  '100%': '#36cfc9',
                }}
                width={80}
              />
            </div>
            <Text type="secondary" style={{ fontSize: '12px', textAlign: 'center' }}>
              {logBookPercentage.toFixed(1)}% vehicles have log books
            </Text>
          </Space>
        </Card>
      </Col>
    </Row>
  );
};
