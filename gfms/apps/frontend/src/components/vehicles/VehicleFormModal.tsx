import React, { useEffect } from 'react';
import { Modal, Form, Input, Select, InputNumber, Switch, message, Row, Col } from 'antd';
import { CarOutlined } from '@ant-design/icons';
import { Vehicle } from '../../types';
import { apiClient } from '../../services/api';

const { Option } = Select;
const { TextArea } = Input;

interface VehicleFormModalProps {
  vehicle?: Vehicle;
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export const VehicleFormModal: React.FC<VehicleFormModalProps> = ({
  vehicle,
  open,
  onClose,
  onSuccess,
}) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = React.useState(false);
  const isEdit = !!vehicle;

  useEffect(() => {
    if (open && vehicle) {
      form.setFieldsValue(vehicle);
    } else if (open) {
      form.resetFields();
    }
  }, [open, vehicle, form]);

  const handleSubmit = async (values: any) => {
    try {
      setLoading(true);
      
      if (isEdit) {
        await apiClient.put(`/vehicles/${vehicle.id}`, values);
        message.success('Vehicle updated successfully');
      } else {
        await apiClient.post('/vehicles', values);
        message.success('Vehicle added successfully');
      }
      
      form.resetFields();
      onSuccess();
      onClose();
    } catch (error: any) {
      const errorMessage = error.response?.data?.message || 'Operation failed';
      message.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  const handleCancel = () => {
    form.resetFields();
    onClose();
  };

  const currentYear = new Date().getFullYear();

  return (
    <Modal
      title={
        <span>
          <CarOutlined style={{ color: '#006600', marginRight: 8 }} />
          {isEdit ? 'Edit Vehicle' : 'Add New Vehicle'}
        </span>
      }
      open={open}
      onCancel={handleCancel}
      onOk={() => form.submit()}
      confirmLoading={loading}
      width={800}
      okText={isEdit ? 'Update' : 'Add'}
      cancelText="Cancel"
    >
      <Form
        form={form}
        layout="vertical"
        onFinish={handleSubmit}
        initialValues={{
          status: 'active',
          fuel_type: 'petrol',
          has_log_book: true,
        }}
      >
        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              name="registration_number"
              label="Registration Number"
              rules={[
                { required: true, message: 'Please enter registration number' },
                { min: 6, message: 'Registration number must be at least 6 characters' },
                { max: 15, message: 'Registration number must not exceed 15 characters' },
              ]}
            >
              <Input placeholder="e.g., GKB 671S" />
            </Form.Item>
          </Col>

          <Col span={12}>
            <Form.Item
              name="status"
              label="Status"
              rules={[{ required: true, message: 'Please select status' }]}
            >
              <Select>
                <Option value="active">Active</Option>
                <Option value="maintenance">Maintenance</Option>
                <Option value="inactive">Inactive</Option>
                <Option value="disposed">Disposed</Option>
              </Select>
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              name="make"
              label="Make"
              rules={[
                { required: true, message: 'Please enter vehicle make' },
                { min: 2, message: 'Make must be at least 2 characters' },
              ]}
            >
              <Input placeholder="e.g., Toyota, Nissan, Land Rover" />
            </Form.Item>
          </Col>

          <Col span={12}>
            <Form.Item
              name="model"
              label="Model"
              rules={[
                { required: true, message: 'Please enter vehicle model' },
                { min: 2, message: 'Model must be at least 2 characters' },
              ]}
            >
              <Input placeholder="e.g., Land Cruiser, Prado, X-Trail" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={8}>
            <Form.Item
              name="year"
              label="Year"
              rules={[
                { required: true, message: 'Please enter year' },
                { type: 'number', min: 1990, message: 'Year must be 1990 or later' },
                { type: 'number', max: currentYear + 1, message: `Year cannot exceed ${currentYear + 1}` },
              ]}
            >
              <InputNumber style={{ width: '100%' }} placeholder="e.g., 2020" />
            </Form.Item>
          </Col>

          <Col span={8}>
            <Form.Item
              name="fuel_type"
              label="Fuel Type"
              rules={[{ required: true, message: 'Please select fuel type' }]}
            >
              <Select>
                <Option value="petrol">Petrol</Option>
                <Option value="diesel">Diesel</Option>
                <Option value="electric">Electric</Option>
                <Option value="hybrid">Hybrid</Option>
              </Select>
            </Form.Item>
          </Col>

          <Col span={8}>
            <Form.Item name="color" label="Color">
              <Input placeholder="e.g., White, Black" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item name="engine_number" label="Engine Number">
              <Input placeholder="e.g., 1HZ-0879638" />
            </Form.Item>
          </Col>

          <Col span={12}>
            <Form.Item name="chassis_number" label="Chassis Number">
              <Input placeholder="e.g., JTELB71J50-7726026" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={8}>
            <Form.Item
              name="mileage"
              label="Mileage (km)"
              rules={[
                { type: 'number', min: 0, message: 'Mileage cannot be negative' },
                { type: 'number', max: 1000000, message: 'Mileage seems too high' },
              ]}
            >
              <InputNumber style={{ width: '100%' }} placeholder="e.g., 50000" />
            </Form.Item>
          </Col>

          <Col span={8}>
            <Form.Item
              name="capacity"
              label="Capacity (passengers)"
              rules={[
                { type: 'number', min: 1, message: 'Capacity must be at least 1' },
                { type: 'number', max: 100, message: 'Capacity cannot exceed 100' },
              ]}
            >
              <InputNumber style={{ width: '100%' }} placeholder="e.g., 5" />
            </Form.Item>
          </Col>

          <Col span={8}>
            <Form.Item name="purchase_year" label="Purchase Year">
              <InputNumber
                style={{ width: '100%' }}
                placeholder="e.g., 2020"
                min={1990}
                max={currentYear}
              />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item name="current_location" label="Current Location">
              <Input placeholder="e.g., POOL, CS OFFICE" />
            </Form.Item>
          </Col>

          <Col span={12}>
            <Form.Item name="responsible_officer" label="Responsible Officer">
              <Input placeholder="e.g., MULEI, Director RMD" />
            </Form.Item>
          </Col>
        </Row>

        <Form.Item name="has_log_book" label="Has Log Book" valuePropName="checked">
          <Switch checkedChildren="YES" unCheckedChildren="NO" />
        </Form.Item>

        <Form.Item name="notes" label="Notes">
          <TextArea
            rows={3}
            placeholder="Additional notes about the vehicle..."
            maxLength={500}
            showCount
          />
        </Form.Item>
      </Form>
    </Modal>
  );
};
