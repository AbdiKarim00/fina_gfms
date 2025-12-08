import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { Form, Input, Button, Alert, Card, Typography, Space } from 'antd';
import { LockOutlined } from '@ant-design/icons';
import { useAuth } from '../contexts/AuthContext';

const { Title, Text, Link } = Typography;

export const VerifyOtpPage: React.FC = () => {
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [form] = Form.useForm();
  const { verifyOtp } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  const userId = location.state?.userId;
  const otpChannel = location.state?.otpChannel || 'email';
  const message = location.state?.message;

  useEffect(() => {
    if (!userId) {
      navigate('/login');
    }
  }, [userId, navigate]);

  const handleSubmit = async (values: { code: string }) => {
    setError('');
    setLoading(true);

    try {
      await verifyOtp(userId, values.code, otpChannel);
      navigate('/dashboard');
    } catch (err: any) {
      console.error('OTP verification error:', err);
      const errorMessage = err.response?.data?.message || err.message || 'Invalid OTP. Please try again.';
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  const handleResendOtp = () => {
    // TODO: Implement resend OTP functionality
    console.log('Resend OTP');
  };

  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4" style={{ background: 'linear-gradient(135deg, #E8F5E9 0%, #FFFFFF 50%, #F1F8E9 100%)' }}>
      <div style={{ maxWidth: 450, width: '100%' }}>
        {/* Logo/Header Section */}
        <Space direction="vertical" size="large" style={{ width: '100%', marginBottom: 32 }} align="center">
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
            <LockOutlined style={{ fontSize: 48, color: '#FFFFFF' }} />
          </div>
          <Space direction="vertical" size={4} align="center">
            <Title level={2} style={{ margin: 0, color: '#000000' }}>Verify OTP</Title>
            <Text type="secondary" style={{ fontSize: 14 }}>
              Enter the code sent to your {otpChannel}
            </Text>
          </Space>
        </Space>

        {/* OTP Card */}
        <Card 
          style={{ 
            borderRadius: 16,
            boxShadow: '0 4px 24px rgba(0, 0, 0, 0.08)'
          }}
        >
          <Space direction="vertical" size="large" style={{ width: '100%' }}>
            {message && (
              <Alert
                message={message}
                type="success"
                showIcon
              />
            )}

            {error && (
              <Alert
                message={error}
                type="error"
                showIcon
                closable
                onClose={() => setError('')}
              />
            )}

            <Form
              form={form}
              name="verify-otp"
              onFinish={handleSubmit}
              layout="vertical"
              size="large"
              requiredMark={false}
            >
              <Form.Item
                label="OTP Code"
                name="code"
                rules={[
                  { required: true, message: 'Please enter the OTP code' },
                  { len: 6, message: 'OTP must be 6 digits' },
                  { pattern: /^\d+$/, message: 'OTP must contain only digits' }
                ]}
              >
                <Input
                  placeholder="000000"
                  maxLength={6}
                  style={{ 
                    fontSize: 24, 
                    textAlign: 'center', 
                    letterSpacing: '0.5em',
                    fontFamily: 'monospace'
                  }}
                  autoComplete="one-time-code"
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
                  {loading ? 'Verifying...' : 'Verify OTP'}
                </Button>
              </Form.Item>
            </Form>

            {/* Helper Links */}
            <div style={{ textAlign: 'center' }}>
              <Space split={<Text type="secondary">•</Text>}>
                <Link onClick={handleResendOtp}>Resend OTP</Link>
                <Link onClick={() => navigate('/login')}>Back to Login</Link>
              </Space>
            </div>
          </Space>
        </Card>

        {/* Helper Text */}
        <div style={{ marginTop: 24, textAlign: 'center' }}>
          <Space direction="vertical" size={8}>
            <Text type="secondary" style={{ fontSize: 12 }}>
              Check your {otpChannel === 'email' ? 'email inbox' : 'phone messages'} for the OTP code
            </Text>
            {otpChannel === 'email' && (
              <Text type="secondary" style={{ fontSize: 12 }}>
                Dev Mode: <Link href="http://localhost:8000/otp-viewer.html" target="_blank">Open OTP Viewer</Link>
              </Text>
            )}
          </Space>
        </div>

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
