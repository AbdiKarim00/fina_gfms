import React from 'react';
import { Spin } from 'antd';

export const PageLoader: React.FC = () => (
  <div
    style={{
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      minHeight: '400px',
      width: '100%',
    }}
  >
    <Spin size="large" />
  </div>
);
