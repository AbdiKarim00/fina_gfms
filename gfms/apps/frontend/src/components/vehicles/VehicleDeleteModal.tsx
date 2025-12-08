import React, { useState } from 'react';
import { Modal, Typography, Space, Alert, message } from 'antd';
import { ExclamationCircleOutlined, CarOutlined } from '@ant-design/icons';
import { Vehicle } from '../../types';
import { apiClient } from '../../services/api';

const { Text, Paragraph } = Typography;

interface VehicleDeleteModalProps {
  vehicle: Vehicle | null;
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export const VehicleDeleteModal: React.FC<VehicleDeleteModalProps> = ({
  vehicle,
  open,
  onClose,
  onSuccess,
}) => {
  const [loading, setLoading] = useState(false);

  if (!vehicle) return null;

  const handleDelete = async () => {
    try {
      setLoading(true);
      await apiClient.delete(`/vehicles/${vehicle.id}`);
      message.success('Vehicle deleted successfully');
      onSuccess();
      onClose();
    } catch (error: any) {
      const errorMessage = error.response?.data?.message || 'Failed to delete vehicle';
      message.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Modal
      title={
        <Space>
          <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />
          <span>Delete Vehicle</span>
        </Space>
      }
      open={open}
      onCancel={onClose}
      onOk={handleDelete}
      confirmLoading={loading}
      okText="Delete"
      cancelText="Cancel"
      okButtonProps={{ danger: true }}
    >
      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        <Alert
          message="Warning"
          description="This action cannot be undone. The vehicle will be permanently deleted from the system."
          type="warning"
          showIcon
        />

        <div>
          <Paragraph>
            Are you sure you want to delete the following vehicle?
          </Paragraph>

          <Space direction="vertical" style={{ width: '100%' }}>
            <Space>
              <CarOutlined style={{ color: '#006600' }} />
              <Text strong>Registration:</Text>
              <Text>{vehicle.registration_number}</Text>
            </Space>

            <Space>
              <Text strong>Make & Model:</Text>
              <Text>{`${vehicle.make} ${vehicle.model}`}</Text>
            </Space>

            <Space>
              <Text strong>Year:</Text>
              <Text>{vehicle.year}</Text>
            </Space>

            <Space>
              <Text strong>Status:</Text>
              <Text>{vehicle.status}</Text>
            </Space>
          </Space>
        </div>
      </Space>
    </Modal>
  );
};
