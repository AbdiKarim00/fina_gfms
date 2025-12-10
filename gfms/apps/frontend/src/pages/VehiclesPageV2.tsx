import React, { useEffect, useState } from 'react';
import {
  Table,
  Input,
  Button,
  Space,
  Tag,
  Card,
  Row,
  Col,
  Select,
  Typography,
  Tooltip,
} from 'antd';
import {
  SearchOutlined,
  PlusOutlined,
  EyeOutlined,
  EditOutlined,
  DeleteOutlined,
  CarOutlined,
} from '@ant-design/icons';
import type { ColumnsType } from 'antd/es/table';
import { apiClient } from '../services/api';
import { Vehicle } from '../types';
import { VehicleDetailsModal } from '../components/vehicles/VehicleDetailsModal';
import { VehicleFormModal } from '../components/vehicles/VehicleFormModal';
import { VehicleDeleteModal } from '../components/vehicles/VehicleDeleteModal';
import { VehicleStats } from '../components/vehicles/VehicleStats';
import { usePermissions } from '../hooks/usePermissions';

const { Title, Text } = Typography;
const { Option } = Select;

export const VehiclesPageV2: React.FC = () => {
  const permissions = usePermissions();
  const [vehicles, setVehicles] = useState<Vehicle[]>([]);
  const [filteredVehicles, setFilteredVehicles] = useState<Vehicle[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchText, setSearchText] = useState('');
  const [statusFilter, setStatusFilter] = useState<string>('all');
  const [fuelTypeFilter, setFuelTypeFilter] = useState<string>('all');
  
  // Modal states
  const [detailsModalOpen, setDetailsModalOpen] = useState(false);
  const [formModalOpen, setFormModalOpen] = useState(false);
  const [deleteModalOpen, setDeleteModalOpen] = useState(false);
  const [selectedVehicle, setSelectedVehicle] = useState<Vehicle | null>(null);

  useEffect(() => {
    fetchVehicles();
  }, []);

  useEffect(() => {
    filterVehicles();
  }, [vehicles, searchText, statusFilter, fuelTypeFilter]);

  const fetchVehicles = async () => {
    try {
      setLoading(true);
      const data = await apiClient.get<{ data: Vehicle[] }>('/vehicles');
      setVehicles(data.data || []);
    } catch (err: any) {
      console.error('Failed to fetch vehicles:', err);
    } finally {
      setLoading(false);
    }
  };

  const filterVehicles = () => {
    let filtered = [...vehicles];

    // Search filter
    if (searchText) {
      filtered = filtered.filter(
        (vehicle) =>
          vehicle.registration_number.toLowerCase().includes(searchText.toLowerCase()) ||
          vehicle.make.toLowerCase().includes(searchText.toLowerCase()) ||
          vehicle.model.toLowerCase().includes(searchText.toLowerCase())
      );
    }

    // Status filter
    if (statusFilter !== 'all') {
      filtered = filtered.filter((vehicle) => vehicle.status === statusFilter);
    }

    // Fuel type filter
    if (fuelTypeFilter !== 'all') {
      filtered = filtered.filter((vehicle) => vehicle.fuel_type === fuelTypeFilter);
    }

    setFilteredVehicles(filtered);
  };

  const handleSearch = (value: string) => {
    setSearchText(value);
  };

  const handleStatusChange = (value: string) => {
    setStatusFilter(value);
  };

  const handleFuelTypeChange = (value: string) => {
    setFuelTypeFilter(value);
  };

  const handleReset = () => {
    setSearchText('');
    setStatusFilter('all');
    setFuelTypeFilter('all');
  };

  // Modal handlers
  const handleViewDetails = (vehicle: Vehicle) => {
    setSelectedVehicle(vehicle);
    setDetailsModalOpen(true);
  };

  const handleAddVehicle = () => {
    setSelectedVehicle(null);
    setFormModalOpen(true);
  };

  const handleEditVehicle = (vehicle: Vehicle) => {
    setSelectedVehicle(vehicle);
    setDetailsModalOpen(false);
    setFormModalOpen(true);
  };

  const handleDeleteVehicle = (vehicle: Vehicle) => {
    setSelectedVehicle(vehicle);
    setDetailsModalOpen(false);
    setDeleteModalOpen(true);
  };

  const handleFormSuccess = () => {
    fetchVehicles();
  };

  const handleDeleteSuccess = () => {
    fetchVehicles();
  };

  // Get role-specific page description
  const getPageDescription = () => {
    switch (permissions.role) {
      case 'super admin':
        return 'Manage all fleet vehicles with full administrative access';
      case 'admin':
        return "Manage your organization's fleet vehicles";
      case 'fleet manager':
        return 'Manage fleet vehicles including registration and maintenance';
      case 'transport officer':
        return 'View available vehicles and check availability for bookings';
      case 'driver':
        return 'View your assigned vehicles and their details';
      default:
        return 'Manage your fleet vehicles including registration, maintenance, and status';
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'active':
        return 'success';
      case 'maintenance':
        return 'warning';
      case 'inactive':
        return 'default';
      default:
        return 'default';
    }
  };

  const columns: ColumnsType<Vehicle> = [
    {
      title: 'Registration',
      dataIndex: 'registration_number',
      key: 'registration_number',
      sorter: (a, b) => a.registration_number.localeCompare(b.registration_number),
      render: (text) => <Text strong>{text}</Text>,
    },
    {
      title: 'Make & Model',
      key: 'make_model',
      render: (_, record) => (
        <Space>
          <CarOutlined style={{ color: '#006600' }} />
          <Text>{`${record.make} ${record.model}`}</Text>
        </Space>
      ),
      sorter: (a, b) => `${a.make} ${a.model}`.localeCompare(`${b.make} ${b.model}`),
    },
    {
      title: 'Year',
      dataIndex: 'year',
      key: 'year',
      sorter: (a, b) => a.year - b.year,
      width: 100,
    },
    {
      title: 'Fuel Type',
      dataIndex: 'fuel_type',
      key: 'fuel_type',
      render: (fuelType) => <Tag color="blue">{fuelType}</Tag>,
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (status) => <Tag color={getStatusColor(status)}>{status.toUpperCase()}</Tag>,
    },
    {
      title: 'Actions',
      key: 'actions',
      width: 150,
      render: (_, record) => (
        <Space size="small">
          <Tooltip title="View Details">
            <Button
              type="text"
              icon={<EyeOutlined />}
              size="small"
              onClick={() => handleViewDetails(record)}
            />
          </Tooltip>
          {permissions.canEditVehicles && (
            <Tooltip title="Edit">
              <Button
                type="text"
                icon={<EditOutlined />}
                size="small"
                onClick={() => handleEditVehicle(record)}
              />
            </Tooltip>
          )}
          {permissions.canDeleteVehicles && (
            <Tooltip title="Delete">
              <Button
                type="text"
                danger
                icon={<DeleteOutlined />}
                size="small"
                onClick={() => handleDeleteVehicle(record)}
              />
            </Tooltip>
          )}
        </Space>
      ),
    },
  ];

  return (
    <div style={{ padding: '24px' }}>
      <Space vertical size="large" style={{ width: '100%' }}>
        {/* Header */}
        <Row justify="space-between" align="middle">
          <Col>
            <Title level={2} style={{ margin: 0 }}>
              Vehicles
            </Title>
            <Text type="secondary">{getPageDescription()}</Text>
          </Col>
          {permissions.canCreateVehicles && (
            <Col>
              <Button
                type="primary"
                icon={<PlusOutlined />}
                size="large"
                onClick={handleAddVehicle}
              >
                Add Vehicle
              </Button>
            </Col>
          )}
        </Row>

        {/* Statistics Cards */}
        <VehicleStats vehicles={vehicles} />

        {/* Filters */}
        <Card>
          <Row gutter={[16, 16]}>
            <Col xs={24} sm={12} md={8}>
              <Input
                placeholder="Search by registration, make, or model"
                prefix={<SearchOutlined />}
                value={searchText}
                onChange={(e) => handleSearch(e.target.value)}
                allowClear
              />
            </Col>
            <Col xs={24} sm={12} md={6}>
              <Select
                style={{ width: '100%' }}
                placeholder="Filter by Status"
                value={statusFilter}
                onChange={handleStatusChange}
              >
                <Option value="all">All Status</Option>
                <Option value="active">Active</Option>
                <Option value="maintenance">Maintenance</Option>
                <Option value="inactive">Inactive</Option>
              </Select>
            </Col>
            <Col xs={24} sm={12} md={6}>
              <Select
                style={{ width: '100%' }}
                placeholder="Filter by Fuel Type"
                value={fuelTypeFilter}
                onChange={handleFuelTypeChange}
              >
                <Option value="all">All Fuel Types</Option>
                <Option value="petrol">Petrol</Option>
                <Option value="diesel">Diesel</Option>
                <Option value="electric">Electric</Option>
                <Option value="hybrid">Hybrid</Option>
              </Select>
            </Col>
            <Col xs={24} sm={12} md={4}>
              <Button onClick={handleReset} block>
                Reset Filters
              </Button>
            </Col>
          </Row>
        </Card>

        {/* Table */}
        <Card>
          <Table
            columns={columns}
            dataSource={filteredVehicles}
            loading={loading}
            rowKey="id"
            pagination={{
              pageSize: 10,
              showSizeChanger: true,
              showTotal: (total) => `Total ${total} vehicles`,
            }}
            locale={{
              emptyText: 'No vehicles found. Add your first vehicle to get started.',
            }}
          />
        </Card>
      </Space>

      {/* Modals */}
      <VehicleDetailsModal
        vehicle={selectedVehicle}
        open={detailsModalOpen}
        onClose={() => setDetailsModalOpen(false)}
        onEdit={handleEditVehicle}
        onDelete={handleDeleteVehicle}
      />

      <VehicleFormModal
        vehicle={selectedVehicle || undefined}
        open={formModalOpen}
        onClose={() => setFormModalOpen(false)}
        onSuccess={handleFormSuccess}
      />

      <VehicleDeleteModal
        vehicle={selectedVehicle}
        open={deleteModalOpen}
        onClose={() => setDeleteModalOpen(false)}
        onSuccess={handleDeleteSuccess}
      />
    </div>
  );
};
