import React from 'react';

export interface PageHeaderProps {
  title: string;
  description?: string;
  extra?: React.ReactNode;
}

export interface DataTableProps<T> {
  data: T[];
  loading?: boolean;
  columns: any[];
  onRowClick?: (record: T) => void;
}
