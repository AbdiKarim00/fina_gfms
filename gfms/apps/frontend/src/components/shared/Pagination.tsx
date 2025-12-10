import React from 'react';
import { Pagination as AntPagination, PaginationProps } from 'antd';

interface CustomPaginationProps extends Omit<PaginationProps, 'onChange'> {
  currentPage: number;
  totalItems: number;
  pageSize?: number;
  onPageChange: (page: number, pageSize: number) => void;
  showSizeChanger?: boolean;
  pageSizeOptions?: string[];
}

export const Pagination: React.FC<CustomPaginationProps> = ({
  currentPage,
  totalItems,
  pageSize = 12,
  onPageChange,
  showSizeChanger = false,
  pageSizeOptions = ['12', '24', '48', '96'],
  ...rest
}) => {
  if (totalItems <= pageSize) {
    return null; // Don't show pagination if all items fit on one page
  }

  return (
    <div style={{ 
      display: 'flex', 
      justifyContent: 'center', 
      marginTop: '24px',
      padding: '16px 0'
    }}>
      <AntPagination
        current={currentPage}
        total={totalItems}
        pageSize={pageSize}
        onChange={onPageChange}
        showSizeChanger={showSizeChanger}
        pageSizeOptions={pageSizeOptions}
        showTotal={(total, range) => `${range[0]}-${range[1]} of ${total} items`}
        {...rest}
      />
    </div>
  );
};
