import React from 'react';
import { Result, Button } from 'antd';
import { ToolOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';

interface UnderDevelopmentProps {
  title?: string;
  description?: string;
}

export const UnderDevelopment: React.FC<UnderDevelopmentProps> = ({
  title = 'Under Development',
  description = 'This feature is currently under development and will be available soon.',
}) => {
  const navigate = useNavigate();

  return (
    <div style={{ padding: '50px 24px', textAlign: 'center' }}>
      <Result
        icon={<ToolOutlined style={{ color: '#faad14' }} />}
        title={title}
        subTitle={description}
        extra={
          <Button type="primary" onClick={() => navigate('/dashboard')}>
            Back to Dashboard
          </Button>
        }
      />
    </div>
  );
};
