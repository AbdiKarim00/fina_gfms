import React, { useState } from 'react';
import {
  Modal,
  Descriptions,
  Tag,
  Space,
  Button,
  Divider,
  Alert,
  Input,
  Typography,
} from 'antd';
import {
  CarOutlined,
  UserOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
} from '@ant-design/icons';
import dayjs from '../../utils/dayjs';
import { Booking } from '../../types';

const { TextArea } = Input;
const { Text } = Typography;

interface BookingDetailsModalProps {
  booking: Booking | null;
  open: boolean;
  onClose: () => void;
  onApprove: (bookingId: number) => void;
  onReject: (bookingId: number, reason: string) => void;
  canApprove: boolean;
}

export const BookingDetailsModal: React.FC<BookingDetailsModalProps> = ({
  booking,
  open,
  onClose,
  onApprove,
  onReject,
  canApprove,
}) => {
  const [showRejectForm, setShowRejectForm] = useState(false);
  const [rejectionReason, setRejectionReason] = useState('');
  const [loading, setLoading] = useState(false);

  if (!booking) return null;

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'pending':
        return 'warning';
      case 'approved':
        return 'success';
      case 'rejected':
        return 'error';
      case 'completed':
        return 'default';
      case 'cancelled':
        return 'default';
      default:
        return 'default';
    }
  };

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high':
        return 'red';
      case 'medium':
        return 'orange';
      case 'low':
        return 'blue';
      default:
        return 'default';
    }
  };

  const handleApprove = async () => {
    setLoading(true);
    await onApprove(booking.id);
    setLoading(false);
    onClose();
  };

  const handleReject = async () => {
    if (!rejectionReason.trim()) return;
    setLoading(true);
    await onReject(booking.id, rejectionReason);
    setLoading(false);
    setShowRejectForm(false);
    setRejectionReason('');
    onClose();
  };

  const duration = dayjs(booking.end_date).diff(dayjs(booking.start_date), 'hour');

  return (
    <Modal
      title={
        <Space>
          <CarOutlined style={{ color: '#006600' }} />
          <span>Booking Details</span>
        </Space>
      }
      open={open}
      onCancel={onClose}
      width={800}
      footer={
        booking.status === 'pending' && canApprove
          ? [
              <Button key="close" onClick={onClose}>
                Close
              </Button>,
              <Button
                key="reject"
                danger
                icon={<CloseCircleOutlined />}
                onClick={() => setShowRejectForm(true)}
              >
                Reject
              </Button>,
              <Button
                key="approve"
                type="primary"
                icon={<CheckCircleOutlined />}
                onClick={handleApprove}
                loading={loading}
              >
                Approve
              </Button>,
            ]
          : [
              <Button key="close" onClick={onClose}>
                Close
              </Button>,
            ]
      }
    >
      <Space vertical size="middle" style={{ width: '100%' }}>
        {/* Status and Priority */}
        <Space>
          <Tag color={getStatusColor(booking.status)} style={{ fontSize: '14px' }}>
            {booking.status.toUpperCase()}
          </Tag>
          <Tag color={getPriorityColor(booking.priority)} style={{ fontSize: '14px' }}>
            {booking.priority.toUpperCase()} PRIORITY
          </Tag>
        </Space>

        {/* Rejection Alert */}
        {booking.status === 'rejected' && booking.rejection_reason && (
          <Alert
            title="Booking Rejected"
            description={booking.rejection_reason}
            type="error"
            showIcon
          />
        )}

        {/* Reject Form */}
        {showRejectForm && (
          <Alert
            title="Reject Booking"
            description={
              <Space vertical style={{ width: '100%', marginTop: '8px' }}>
                <Text>Please provide a reason for rejecting this booking:</Text>
                <TextArea
                  rows={4}
                  value={rejectionReason}
                  onChange={(e) => setRejectionReason(e.target.value)}
                  placeholder="Enter rejection reason (minimum 10 characters)..."
                  maxLength={500}
                  showCount
                />
                <Space>
                  <Button
                    type="primary"
                    danger
                    onClick={handleReject}
                    disabled={!rejectionReason.trim() || rejectionReason.length < 10}
                    loading={loading}
                  >
                    Confirm Rejection
                  </Button>
                  <Button onClick={() => setShowRejectForm(false)}>Cancel</Button>
                </Space>
              </Space>
            }
            type="warning"
            showIcon
          />
        )}

        <Divider />

        {/* Booking Information */}
        <Descriptions bordered column={2} size="small">
          <Descriptions.Item label="Vehicle" span={2}>
            <Space>
              <CarOutlined style={{ color: '#006600' }} />
              <Text strong>{booking.vehicle?.registration_number || 'N/A'}</Text>
              <Text type="secondary">
                {booking.vehicle?.make} {booking.vehicle?.model} ({booking.vehicle?.year})
              </Text>
            </Space>
          </Descriptions.Item>

          <Descriptions.Item label="Requester" span={2}>
            <Space>
              <UserOutlined />
              <Text>{booking.requester?.name || 'Unknown'}</Text>
              <Text type="secondary">({booking.requester?.personal_number})</Text>
            </Space>
          </Descriptions.Item>

          {booking.driver && (
            <Descriptions.Item label="Assigned Driver" span={2}>
              <Space>
                <UserOutlined />
                <Text>{booking.driver.name}</Text>
                <Text type="secondary">({booking.driver.personal_number})</Text>
              </Space>
            </Descriptions.Item>
          )}

          <Descriptions.Item label="Start Date">
            {dayjs(booking.start_date).format('MMM D, YYYY HH:mm')}
          </Descriptions.Item>

          <Descriptions.Item label="End Date">
            {dayjs(booking.end_date).format('MMM D, YYYY HH:mm')}
          </Descriptions.Item>

          <Descriptions.Item label="Duration" span={2}>
            {duration} hours ({Math.floor(duration / 24)} days, {duration % 24} hours)
          </Descriptions.Item>

          <Descriptions.Item label="Destination" span={2}>
            {booking.destination}
          </Descriptions.Item>

          <Descriptions.Item label="Passengers">
            {booking.passengers} passenger{booking.passengers > 1 ? 's' : ''}
          </Descriptions.Item>

          <Descriptions.Item label="Priority">
            <Tag color={getPriorityColor(booking.priority)}>
              {booking.priority.toUpperCase()}
            </Tag>
          </Descriptions.Item>

          <Descriptions.Item label="Purpose" span={2}>
            {booking.purpose}
          </Descriptions.Item>

          {booking.notes && (
            <Descriptions.Item label="Notes" span={2}>
              {booking.notes}
            </Descriptions.Item>
          )}

          <Descriptions.Item label="Created At">
            {dayjs(booking.created_at).format('MMM D, YYYY HH:mm')}
          </Descriptions.Item>

          <Descriptions.Item label="Requested">
            {dayjs(booking.created_at).fromNow()}
          </Descriptions.Item>

          {booking.approved_by && (
            <>
              <Descriptions.Item label="Approved/Rejected By" span={2}>
                <Space>
                  <UserOutlined />
                  <Text>{booking.approver?.name || 'Unknown'}</Text>
                  <Text type="secondary">
                    on {dayjs(booking.approved_at).format('MMM D, YYYY HH:mm')}
                  </Text>
                </Space>
              </Descriptions.Item>
            </>
          )}
        </Descriptions>
      </Space>
    </Modal>
  );
};
