import React, { useEffect, useState } from 'react';
import {
  Card,
  Row,
  Col,
  Statistic,
  Select,
  Input,
  Button,
  Space,
  Typography,
  Badge,
  App,
  Spin,
  Progress,
} from 'antd';
import {
  FileTextOutlined,
  ClockCircleOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
  SearchOutlined,
  ReloadOutlined,
} from '@ant-design/icons';
import { apiClient } from '../services/api';
import { Booking, BookingFilters } from '../types';
import { BookingQueue } from '../components/bookings/BookingQueue';
import { BookingDetailsModal } from '../components/bookings/BookingDetailsModal';
import { Pagination } from '../components/shared/Pagination';
import { usePermissions } from '../hooks/usePermissions';

const { Title, Text } = Typography;
const { Option } = Select;

export const BookingsPage: React.FC = () => {
  const { message } = App.useApp();
  const permissions = usePermissions();
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [statistics, setStatistics] = useState({
    total: 0,
    pending: 0,
    approved: 0,
    rejected: 0,
  });
  const [filters, setFilters] = useState<BookingFilters>({
    status: '', // Start with all bookings to avoid permission issues
  });
  const [searchText, setSearchText] = useState('');
  const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);
  const [detailsModalOpen, setDetailsModalOpen] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [pageSize, setPageSize] = useState(12);

  useEffect(() => {
    fetchBookings();
    fetchStatistics();
    setCurrentPage(1); // Reset to first page when filters change
  }, [filters]);

  const fetchBookings = async () => {
    try {
      setLoading(true);
      
      const params = new URLSearchParams();
      if (filters.status) params.append('status', filters.status);
      if (filters.priority) params.append('priority', filters.priority);

      const response = await apiClient.get<{ success: boolean; data: Booking[] }>(
        `/bookings?${params.toString()}`
      );
      
      setBookings(response.data || []);
    } catch (error: any) {
      // User-friendly error messages
      let errorMessage = 'Unable to load bookings. Please try again.';
      
      if (error.response?.status === 403) {
        errorMessage = 'You don\'t have permission to view bookings. Please contact your administrator.';
      } else if (error.response?.status === 500) {
        errorMessage = 'Server error. Our team has been notified. Please try again later.';
      } else if (error.response?.status === 404) {
        errorMessage = 'Bookings service not found. Please contact support.';
      } else if (!navigator.onLine) {
        errorMessage = 'No internet connection. Please check your network.';
      }
      
      message.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  const fetchStatistics = async () => {
    try {
      const response = await apiClient.get<{ success: boolean; data: any }>(
        '/bookings/statistics'
      );
      setStatistics(response.data);
    } catch (error: any) {
      // Silently fail for statistics - not critical
      console.error('Failed to fetch statistics:', error.message);
    }
  };

  const handleApprove = async (bookingId: number) => {
    try {
      await apiClient.post(`/bookings/${bookingId}/approve`);
      message.success('Booking approved successfully! 🎉');
      fetchBookings();
      fetchStatistics();
    } catch (error: any) {
      let errorMsg = 'Unable to approve booking. Please try again.';
      
      if (error.response?.status === 403) {
        errorMsg = 'You don\'t have permission to approve bookings';
      } else if (error.response?.status === 400) {
        // Extract the actual error message from backend
        const backendMessage = error.response?.data?.message;
        if (backendMessage) {
          errorMsg = backendMessage;
        }
      }
      
      message.error(errorMsg, 6);
    }
  };

  const handleReject = async (bookingId: number, reason: string) => {
    try {
      await apiClient.post(`/bookings/${bookingId}/reject`, { reason });
      message.success('Booking rejected');
      fetchBookings();
      fetchStatistics();
    } catch (error: any) {
      let errorMsg = 'Unable to reject booking. Please try again.';
      
      if (error.response?.status === 403) {
        errorMsg = 'You don\'t have permission to reject bookings';
      } else if (error.response?.status === 400) {
        // Show the actual error message from backend
        errorMsg = error.response?.data?.message || 'This booking cannot be rejected';
      }
      
      message.error(errorMsg, 5);
    }
  };

  const handleViewDetails = (booking: Booking) => {
    setSelectedBooking(booking);
    setDetailsModalOpen(true);
  };

  const handleRefresh = async () => {
    setRefreshing(true);
    await Promise.all([fetchBookings(), fetchStatistics()]);
    setRefreshing(false);
    message.success('Bookings refreshed');
  };

  const filteredBookings = bookings.filter((booking) => {
    if (!searchText) return true;
    const search = searchText.toLowerCase();
    return (
      booking.destination.toLowerCase().includes(search) ||
      booking.purpose.toLowerCase().includes(search) ||
      booking.vehicle?.registration_number.toLowerCase().includes(search) ||
      booking.requester?.name.toLowerCase().includes(search)
    );
  });

  // Paginate filtered bookings
  const startIndex = (currentPage - 1) * pageSize;
  const endIndex = startIndex + pageSize;
  const paginatedBookings = filteredBookings.slice(startIndex, endIndex);

  const handlePageChange = (page: number, newPageSize: number) => {
    setCurrentPage(page);
    if (newPageSize !== pageSize) {
      setPageSize(newPageSize);
      setCurrentPage(1); // Reset to first page when page size changes
    }
  };

  if (!permissions.canViewBookings) {
    return (
      <div style={{ padding: '24px' }}>
        <Card>
          <Text>You don't have permission to view bookings.</Text>
        </Card>
      </div>
    );
  }

  return (
    <div style={{ padding: '24px' }}>
      <Space vertical size="large" style={{ width: '100%' }}>
        {/* Header */}
        <Row justify="space-between" align="middle">
          <Col>
            <Title level={2} style={{ margin: 0 }}>
              Vehicle Bookings
            </Title>
            <Text type="secondary">Review and approve vehicle booking requests</Text>
          </Col>
          <Col>
            <Button 
              icon={<ReloadOutlined spin={refreshing} />} 
              onClick={handleRefresh}
              loading={refreshing}
            >
              Refresh
            </Button>
          </Col>
        </Row>

        {/* Statistics Cards */}
        <Row gutter={[16, 16]}>
          <Col xs={24} sm={12} lg={6}>
            <Card hoverable style={{ borderRadius: '12px' }}>
              <Space vertical size="small" style={{ width: '100%' }}>
                <Space>
                  <FileTextOutlined style={{ fontSize: '24px', color: '#1890ff' }} />
                  <Text strong style={{ fontSize: '16px' }}>Total Bookings</Text>
                </Space>
                <Statistic
                  value={statistics.total}
                  styles={{ content: { color: '#1890ff', fontSize: '32px', fontWeight: 'bold' } }}
                />
                <Text type="secondary" style={{ fontSize: '12px' }}>
                  All booking requests
                </Text>
              </Space>
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable style={{ borderRadius: '12px' }}>
              <Space vertical size="small" style={{ width: '100%' }}>
                <Space>
                  <ClockCircleOutlined style={{ fontSize: '24px', color: '#faad14' }} />
                  <Text strong style={{ fontSize: '16px' }}>Pending</Text>
                </Space>
                <Statistic
                  value={statistics.pending}
                  styles={{ content: { color: '#faad14', fontSize: '32px', fontWeight: 'bold' } }}
                />
                <div>
                  <Progress
                    percent={statistics.total > 0 ? Math.round((statistics.pending / statistics.total) * 100) : 0}
                    strokeColor="#faad14"
                    showInfo={false}
                    size="small"
                  />
                  <Text type="secondary" style={{ fontSize: '12px' }}>
                    {statistics.total > 0 ? `${Math.round((statistics.pending / statistics.total) * 100)}% awaiting approval` : 'No pending bookings'}
                  </Text>
                </div>
              </Space>
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable style={{ borderRadius: '12px' }}>
              <Space vertical size="small" style={{ width: '100%' }}>
                <Space>
                  <CheckCircleOutlined style={{ fontSize: '24px', color: '#52c41a' }} />
                  <Text strong style={{ fontSize: '16px' }}>Approved</Text>
                </Space>
                <Statistic
                  value={statistics.approved}
                  styles={{ content: { color: '#52c41a', fontSize: '32px', fontWeight: 'bold' } }}
                />
                <div>
                  <Progress
                    percent={statistics.total > 0 ? Math.round((statistics.approved / statistics.total) * 100) : 0}
                    strokeColor="#52c41a"
                    showInfo={false}
                    size="small"
                  />
                  <Text type="secondary" style={{ fontSize: '12px' }}>
                    {statistics.total > 0 ? `${Math.round((statistics.approved / statistics.total) * 100)}% approval rate` : 'No approved bookings'}
                  </Text>
                </div>
              </Space>
            </Card>
          </Col>

          <Col xs={24} sm={12} lg={6}>
            <Card hoverable style={{ borderRadius: '12px' }}>
              <Space vertical size="small" style={{ width: '100%' }}>
                <Space>
                  <CloseCircleOutlined style={{ fontSize: '24px', color: '#ff4d4f' }} />
                  <Text strong style={{ fontSize: '16px' }}>Rejected</Text>
                </Space>
                <Statistic
                  value={statistics.rejected}
                  styles={{ content: { color: '#ff4d4f', fontSize: '32px', fontWeight: 'bold' } }}
                />
                <div>
                  <Progress
                    percent={statistics.total > 0 ? Math.round((statistics.rejected / statistics.total) * 100) : 0}
                    strokeColor="#ff4d4f"
                    showInfo={false}
                    size="small"
                  />
                  <Text type="secondary" style={{ fontSize: '12px' }}>
                    {statistics.total > 0 ? `${Math.round((statistics.rejected / statistics.total) * 100)}% rejection rate` : 'No rejected bookings'}
                  </Text>
                </div>
              </Space>
            </Card>
          </Col>
        </Row>

        {/* Filters */}
        <Card>
          <Row gutter={[16, 16]}>
            <Col xs={24} sm={12} md={8}>
              <Input
                placeholder="Search by destination, purpose, vehicle..."
                prefix={<SearchOutlined />}
                value={searchText}
                onChange={(e) => setSearchText(e.target.value)}
                allowClear
              />
            </Col>

            <Col xs={24} sm={12} md={6}>
              <Select
                style={{ width: '100%' }}
                placeholder="Filter by Status"
                value={filters.status || ''}
                onChange={(value) => setFilters({ ...filters, status: value })}
              >
                <Option value="">All Status</Option>
                <Option value="pending">
                  <Badge status="warning" text="Pending" />
                </Option>
                <Option value="approved">
                  <Badge status="success" text="Approved" />
                </Option>
                <Option value="rejected">
                  <Badge status="error" text="Rejected" />
                </Option>
                <Option value="completed">
                  <Badge status="default" text="Completed" />
                </Option>
              </Select>
            </Col>

            <Col xs={24} sm={12} md={6}>
              <Select
                style={{ width: '100%' }}
                placeholder="Filter by Priority"
                value={filters.priority}
                onChange={(value) => setFilters({ ...filters, priority: value })}
                allowClear
              >
                <Option value="high">
                  <Badge color="red" text="High Priority" />
                </Option>
                <Option value="medium">
                  <Badge color="orange" text="Medium Priority" />
                </Option>
                <Option value="low">
                  <Badge color="blue" text="Low Priority" />
                </Option>
              </Select>
            </Col>

            <Col xs={24} sm={12} md={4}>
              <Button
                onClick={() => {
                  setFilters({ status: '' });
                  setSearchText('');
                }}
                block
              >
                Reset Filters
              </Button>
            </Col>
          </Row>
        </Card>

        {/* Booking Queue */}
        {loading ? (
          <Card>
            <div style={{ textAlign: 'center', padding: '40px 0' }}>
              <Spin size="large" />
            </div>
          </Card>
        ) : (
          <>
            <BookingQueue
              bookings={paginatedBookings}
              onApprove={handleApprove}
              onReject={handleReject}
              onViewDetails={handleViewDetails}
              canApprove={permissions.canApproveBookings}
            />
            
            <Pagination
              currentPage={currentPage}
              totalItems={filteredBookings.length}
              pageSize={pageSize}
              onPageChange={handlePageChange}
              showSizeChanger
            />
          </>
        )}
      </Space>

      {/* Booking Details Modal */}
      <BookingDetailsModal
        booking={selectedBooking}
        open={detailsModalOpen}
        onClose={() => setDetailsModalOpen(false)}
        onApprove={handleApprove}
        onReject={handleReject}
        canApprove={permissions.canApproveBookings}
      />
    </div>
  );
};
