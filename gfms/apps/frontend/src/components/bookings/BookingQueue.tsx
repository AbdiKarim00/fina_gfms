import React from 'react';
import { Row, Col, Empty } from 'antd';
import { Booking } from '../../types';
import { BookingCard } from './BookingCard';

interface BookingQueueProps {
  bookings: Booking[];
  onApprove: (bookingId: number) => void;
  onReject: (bookingId: number, reason: string) => void;
  onViewDetails: (booking: Booking) => void;
  canApprove: boolean;
}

export const BookingQueue: React.FC<BookingQueueProps> = ({
  bookings,
  onApprove,
  onReject,
  onViewDetails,
  canApprove,
}) => {
  if (bookings.length === 0) {
    return (
      <Empty
        description="No bookings found"
        style={{ padding: '50px' }}
      />
    );
  }

  return (
    <Row gutter={[16, 16]}>
      {bookings.map((booking) => (
        <Col xs={24} sm={24} md={12} lg={12} xl={8} xxl={8} key={booking.id}>
          <BookingCard
            booking={booking}
            onApprove={onApprove}
            onReject={onReject}
            onViewDetails={onViewDetails}
            canApprove={canApprove}
          />
        </Col>
      ))}
    </Row>
  );
};
