import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Form, Input, Button, Alert, Card, Typography, Space } from 'antd';
import { UserOutlined, LockOutlined, SafetyOutlined } from '@ant-design/icons';
import { useAuth } from '../contexts/AuthContext';

const { Title, Text } = Typography;

export const LoginPage: React.FC = () => {
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (values: { personal_number: string; password: string }) => {
    setError('');
    setLoading(true);

    try {
      const result = await login(values);
      // Navigate to OTP verification page with user_id and channel
      navigate('/verify-otp', {
        state: {
          userId: result.user_id,
          otpChannel: result.otp_channel,
          message: result.message,
        },
      });
    } catch (err: any) {
      console.error('Login error:', err);
      console.error('Response:', err.response);
      const errorMessage = err.response?.data?.message || err.message || 'Login failed. Please try again.';
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4" style={{ background: 'linear-gradient(135deg, #E8F5E9 0%, #FFFFFF 50%, #F1F8E9 100%)' }}>
      <div style={{ maxWidth: 450, width: '100%' }}>
        {/* Logo/Header Section */}
        <Space vertical size="large" style={{ width: '100%', marginBottom: 32 }} align="center">
          <div style={{ 
            width: 80, 
            height: 80, 
            background: 'linear-gradient(135deg, #006600 0%, #009900 100%)',
            borderRadius: 16,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 4px 12px rgba(0, 102, 0, 0.2)'
          }}>
            <SafetyOutlined style={{ fontSize: 48, color: '#FFFFFF' }} />
          </div>
          <Space vertical size={4} align="center">
            <Title level={2} style={{ margin: 0, color: '#000000' }}>GFMS</Title>
            <Text type="secondary" style={{ fontSize: 14 }}>Kenya Government Fleet Management System</Text>
          </Space>
        </Space>

        {/* Login Card */}
        <Card 
          style={{ 
            borderRadius: 16,
            boxShadow: '0 4px 24px rgba(0, 0, 0, 0.08)'
          }}
        >
          <Space vertical size="large" style={{ width: '100%' }}>
            <Title level={4} style={{ margin: 0 }}>Sign in to your account</Title>
            
            {error && (
              <Alert
                title={error}
                type="error"
                showIcon
                closable
                onClose={() => setError('')}
              />
            )}

            <Form
              form={form}
              name="login"
              onFinish={handleSubmit}
              layout="vertical"
              size="large"
              requiredMark={false}
            >
              <Form.Item
                label="Personal Number"
                name="personal_number"
                rules={[
                  { required: true, message: 'Please enter your personal number' },
                  { pattern: /^\d+$/, message: 'Personal number must contain only digits' }
                ]}
              >
                <Input
                  prefix={<UserOutlined style={{ color: 'rgba(0,0,0,0.25)' }} />}
                  placeholder="123456"
                  autoComplete="username"
                />
              </Form.Item>

              <Form.Item
                label="Password"
                name="password"
                rules={[
                  { required: true, message: 'Please enter your password' },
                  { min: 8, message: 'Password must be at least 8 characters' }
                ]}
              >
                <Input.Password
                  prefix={<LockOutlined style={{ color: 'rgba(0,0,0,0.25)' }} />}
                  placeholder="••••••••"
                  autoComplete="current-password"
                />
              </Form.Item>

              <Form.Item style={{ marginBottom: 0 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  block
                  size="large"
                  style={{ height: 48, fontWeight: 600 }}
                >
                  {loading ? 'Signing in...' : 'Sign in'}
                </Button>
              </Form.Item>
            </Form>

            {/* Demo Credentials */}
            <Alert
              title={
                <Space size="small">
                  <Text strong>Demo:</Text>
                  <Text code>123456</Text>
                  <Text type="secondary">/</Text>
                  <Text code>password</Text>
                </Space>
              }
              type="info"
              showIcon
            />
          </Space>
        </Card>

        {/* Footer */}
        <div style={{ marginTop: 24, textAlign: 'center' }}>
          <Text type="secondary" style={{ fontSize: 12 }}>
            © 2025 Kenya Government. All rights reserved.
          </Text>
        </div>
      </div>
    </div>
  );
};
