import React from 'react';
import { Modal, Descriptions, Tag, Space, Button, Divider } from 'antd';
import { EditOutlined, DeleteOutlined, CarOutlined } from '@ant-design/icons';
import { Vehicle } from '../../types';

interface VehicleDetailsModalProps {
  vehicle: Vehicle | null;
  open: boolean;
  onClose: () => void;
  onEdit?: (vehicle: Vehicle) => void;
  onDelete?: (vehicle: Vehicle) => void;
}

export const VehicleDetailsModal: React.FC<VehicleDetailsModalProps> = ({
  vehicle,
  open,
  onClose,
  onEdit,
  onDelete,
}) => {
  if (!vehicle) return null;

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

  return (
    <Modal
      title={
        <Space>
          <CarOutlined style={{ color: '#006600' }} />
          <span>Vehicle Details</span>
        </Space>
      }
      open={open}
      onCancel={onClose}
      width={800}
      footer={[
        <Button key="close" onClick={onClose}>
          Close
        </Button>,
        onEdit && (
          <Button
            key="edit"
            type="primary"
            icon={<EditOutlined />}
            onClick={() => onEdit(vehicle)}
          >
            Edit
          </Button>
        ),
        onDelete && (
          <Button
            key="delete"
            danger
            icon={<DeleteOutlined />}
            onClick={() => onDelete(vehicle)}
          >
            Delete
          </Button>
        ),
      ]}
    >
      <Descriptions bordered column={2} size="small">
        <Descriptions.Item label="Registration Number" span={2}>
          <strong style={{ fontSize: '16px' }}>{vehicle.registration_number}</strong>
        </Descriptions.Item>

        <Descriptions.Item label="Make">{vehicle.make}</Descriptions.Item>
        <Descriptions.Item label="Model">{vehicle.model}</Descriptions.Item>

        <Descriptions.Item label="Year">{vehicle.year}</Descriptions.Item>
        <Descriptions.Item label="Fuel Type">
          <Tag color="blue">{vehicle.fuel_type}</Tag>
        </Descriptions.Item>

        <Descriptions.Item label="Status" span={2}>
          <Tag color={getStatusColor(vehicle.status)}>{vehicle.status.toUpperCase()}</Tag>
        </Descriptions.Item>
      </Descriptions>

      <Divider>Additional Information</Divider>

      <Descriptions bordered column={2} size="small">
        <Descriptions.Item label="Engine Number">
          {vehicle.engine_number || 'N/A'}
        </Descriptions.Item>
        <Descriptions.Item label="Chassis Number">
          {vehicle.chassis_number || 'N/A'}
        </Descriptions.Item>

        <Descriptions.Item label="Color">{vehicle.color || 'N/A'}</Descriptions.Item>
        <Descriptions.Item label="Mileage">
          {vehicle.mileage ? `${vehicle.mileage.toLocaleString()} km` : 'N/A'}
        </Descriptions.Item>

        <Descriptions.Item label="Capacity">
          {vehicle.capacity ? `${vehicle.capacity} passengers` : 'N/A'}
        </Descriptions.Item>
        <Descriptions.Item label="Purchase Year">
          {vehicle.purchase_year || 'N/A'}
        </Descriptions.Item>

        <Descriptions.Item label="Current Location" span={2}>
          {vehicle.current_location || 'N/A'}
        </Descriptions.Item>

        <Descriptions.Item label="Responsible Officer" span={2}>
          {vehicle.responsible_officer || 'N/A'}
        </Descriptions.Item>

        <Descriptions.Item label="Has Log Book" span={2}>
          <Tag color={vehicle.has_log_book ? 'success' : 'error'}>
            {vehicle.has_log_book ? 'YES' : 'NO'}
          </Tag>
        </Descriptions.Item>

        {vehicle.notes && (
          <Descriptions.Item label="Notes" span={2}>
            {vehicle.notes}
          </Descriptions.Item>
        )}
      </Descriptions>
    </Modal>
  );
};
