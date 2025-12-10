import React, { useState } from 'react';
import {
  Card,
  Tag,
  Space,
  Button,
  Typography,
  Divider,
  Badge,
  Modal,
  Input,
  Tooltip,
} from 'antd';
import {
  CarOutlined,
  UserOutlined,
  EnvironmentOutlined,
  CalendarOutlined,
  TeamOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
  EyeOutlined,
  ClockCircleOutlined,
} from '@ant-design/icons';
import dayjs from '../../utils/dayjs';
import { Booking } from '../../types';

const { Text, Paragraph } = Typography;
const { TextArea } = Input;

interface BookingCardProps {
  booking: Booking;
  onApprove: (bookingId: number) => void;
  onReject: (bookingId: number, reason: string) => void;
  onViewDetails: (booking: Booking) => void;
  canApprove: boolean;
}

export const BookingCard: React.FC<BookingCardProps> = ({
  booking,
  onApprove,
  onReject,
  onViewDetails,
  canApprove,
}) => {
  const [rejectModalOpen, setRejectModalOpen] = useState(false);
  const [rejectionReason, setRejectionReason] = useState('');
  const [loading, setLoading] = useState(false);

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
  };

  const handleRejectSubmit = async () => {
    if (!rejectionReason.trim()) {
      return;
    }
    setLoading(true);
    await onReject(booking.id, rejectionReason);
    setLoading(false);
    setRejectModalOpen(false);
    setRejectionReason('');
  };

  const duration = dayjs(booking.end_date).diff(dayjs(booking.start_date), 'hour');

  return (
    <>
      <Badge.Ribbon
        text={booking.priority.toUpperCase()}
        color={getPriorityColor(booking.priority)}
      >
        <Card
          hoverable
          style={{ height: '100%' }}
          actions={
            booking.status === 'pending' && canApprove
              ? [
                  <Tooltip title="View Details">
                    <Button
                      type="text"
                      icon={<EyeOutlined />}
                      onClick={() => onViewDetails(booking)}
                    >
                      Details
                    </Button>
                  </Tooltip>,
                  <Tooltip title="Approve Booking">
                    <Button
                      type="text"
                      icon={<CheckCircleOutlined />}
                      onClick={handleApprove}
                      loading={loading}
                      style={{ color: '#52c41a' }}
                    >
                      Approve
                    </Button>
                  </Tooltip>,
                  <Tooltip title="Reject Booking">
                    <Button
                      type="text"
                      danger
                      icon={<CloseCircleOutlined />}
                      onClick={() => setRejectModalOpen(true)}
                    >
                      Reject
                    </Button>
                  </Tooltip>,
                ]
              : [
                  <Button
                    type="text"
                    icon={<EyeOutlined />}
                    onClick={() => onViewDetails(booking)}
                  >
                    View Details
                  </Button>,
                ]
          }
        >
          <Space vertical size="small" style={{ width: '100%' }}>
            {/* Status */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <Tag color={getStatusColor(booking.status)}>
                {booking.status.toUpperCase()}
              </Tag>
              <Text type="secondary" style={{ fontSize: '12px' }}>
                <ClockCircleOutlined /> {dayjs(booking.created_at).fromNow()}
              </Text>
            </div>

            <Divider style={{ margin: '8px 0' }} />

            {/* Vehicle */}
            <Space>
              <CarOutlined style={{ color: '#006600' }} />
              <Text strong>{booking.vehicle?.registration_number || 'N/A'}</Text>
              <Text type="secondary">
                {booking.vehicle?.make} {booking.vehicle?.model}
              </Text>
            </Space>

            {/* Requester */}
            <Space>
              <UserOutlined />
              <Text>{booking.requester?.name || 'Unknown'}</Text>
            </Space>

            {/* Destination */}
            <Space>
              <EnvironmentOutlined style={{ color: '#1890ff' }} />
              <Text strong>{booking.destination}</Text>
            </Space>

            {/* Date & Time */}
            <Space vertical size={0} style={{ width: '100%' }}>
              <Space>
                <CalendarOutlined />
                <Text type="secondary">
                  {dayjs(booking.start_date).format('MMM D, YYYY HH:mm')}
                </Text>
              </Space>
              <Text type="secondary" style={{ marginLeft: '24px' }}>
                to {dayjs(booking.end_date).format('MMM D, YYYY HH:mm')}
              </Text>
              <Text type="secondary" style={{ marginLeft: '24px', fontSize: '12px' }}>
                ({duration} hours)
              </Text>
            </Space>

            {/* Passengers */}
            <Space>
              <TeamOutlined />
              <Text>{booking.passengers} passenger{booking.passengers > 1 ? 's' : ''}</Text>
            </Space>

            <Divider style={{ margin: '8px 0' }} />

            {/* Purpose */}
            <Paragraph
              ellipsis={{ rows: 2, expandable: false }}
              style={{ margin: 0, fontSize: '13px' }}
            >
              <Text type="secondary">Purpose:</Text> {booking.purpose}
            </Paragraph>
          </Space>
        </Card>
      </Badge.Ribbon>

      {/* Reject Modal */}
      <Modal
        title="Reject Booking"
        open={rejectModalOpen}
        onOk={handleRejectSubmit}
        onCancel={() => {
          setRejectModalOpen(false);
          setRejectionReason('');
        }}
        okText="Reject"
        okButtonProps={{ danger: true, disabled: !rejectionReason.trim() }}
        confirmLoading={loading}
      >
        <Space vertical size="middle" style={{ width: '100%' }}>
          <Text>Please provide a reason for rejecting this booking:</Text>
          <TextArea
            rows={4}
            value={rejectionReason}
            onChange={(e) => setRejectionReason(e.target.value)}
            placeholder="Enter rejection reason (minimum 10 characters)..."
            maxLength={500}
            showCount
          />
        </Space>
      </Modal>
    </>
  );
};
