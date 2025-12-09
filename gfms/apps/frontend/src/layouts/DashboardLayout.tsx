import React, { useState } from 'react';
import { Outlet, useNavigate, useLocation } from 'react-router-dom';
import { Layout, Menu, Avatar, Dropdown, Typography, Space, Badge } from 'antd';
import {
  UserOutlined,
  LogoutOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined,
} from '@ant-design/icons';
import type { MenuProps } from 'antd';
import { useAuth } from '../contexts/AuthContext';
import { getRoleMenuItems } from '../utils/roleMenus';

const { Header, Sider, Content } = Layout;
const { Text } = Typography;

export const DashboardLayout: React.FC = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [collapsed, setCollapsed] = useState(false);

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  // Get role-specific menu items
  const roleMenuItems = getRoleMenuItems(user?.roles || []);

  const menuItems: MenuProps['items'] = roleMenuItems.map((item) => ({
    key: item.key,
    icon: item.icon,
    label: item.badge ? (
      <Space>
        {item.label}
        <Badge count={item.badge} style={{ backgroundColor: '#faad14' }} />
      </Space>
    ) : (
      item.label
    ),
    onClick: () => navigate(item.path),
  }));

  const userMenuItems: MenuProps['items'] = [
    {
      key: 'profile',
      icon: <UserOutlined />,
      label: 'Profile',
    },
    {
      type: 'divider',
    },
    {
      key: 'logout',
      icon: <LogoutOutlined />,
      label: 'Logout',
      danger: true,
      onClick: handleLogout,
    },
  ];

  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Sider
        trigger={null}
        collapsible
        collapsed={collapsed}
        style={{
          overflow: 'auto',
          height: '100vh',
          position: 'fixed',
          left: 0,
          top: 0,
          bottom: 0,
        }}
      >
        <div
          style={{
            height: 64,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            flexDirection: 'column',
            color: '#fff',
            fontSize: collapsed ? '12px' : '16px',
            fontWeight: 'bold',
            transition: 'all 0.2s',
            padding: '8px',
          }}
        >
          <div>{collapsed ? 'GFMS' : 'Kenya GFMS'}</div>
          {!collapsed && user?.roles && (
            <Text style={{ color: 'rgba(255,255,255,0.65)', fontSize: '11px' }}>
              {user.roles[0]}
            </Text>
          )}
        </div>
        <Menu
          theme="dark"
          mode="inline"
          selectedKeys={[location.pathname]}
          items={menuItems}
        />
      </Sider>
      <Layout style={{ marginLeft: collapsed ? 80 : 200, transition: 'all 0.2s' }}>
        <Header
          style={{
            padding: '0 24px',
            background: '#fff',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            boxShadow: '0 1px 4px rgba(0,21,41,.08)',
          }}
        >
          <Space>
            {React.createElement(collapsed ? MenuUnfoldOutlined : MenuFoldOutlined, {
              style: { fontSize: '18px', cursor: 'pointer' },
              onClick: () => setCollapsed(!collapsed),
            })}
          </Space>
          <Dropdown menu={{ items: userMenuItems }} placement="bottomRight">
            <Space style={{ cursor: 'pointer' }}>
              <Avatar style={{ backgroundColor: '#006600' }} icon={<UserOutlined />} />
              <Text>{user?.name}</Text>
            </Space>
          </Dropdown>
        </Header>
        <Content
          style={{
            margin: '24px 16px',
            padding: 24,
            minHeight: 280,
            background: '#f0f2f5',
          }}
        >
          <Outlet />
        </Content>
      </Layout>
    </Layout>
  );
};
