import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { User, LoginCredentials, LoginResponse, VerifyOtpResponse } from '../types';
import { apiClient } from '../services/api';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (credentials: LoginCredentials) => Promise<{ user_id: number; otp_channel: string; message: string }>;
  verifyOtp: (userId: number, code: string, otpChannel?: string) => Promise<void>;
  logout: () => void;
  isAuthenticated: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      try {
        const response = await apiClient.get<{ success: boolean; data: User }>('/auth/me');
        setUser(response.data);
      } catch {
        // Silently handle auth check errors and remove token
        localStorage.removeItem('auth_token');
      }
    }
    setLoading(false);
  };

  const login = async (credentials: LoginCredentials) => {
    const response = await apiClient.post<LoginResponse>('/auth/login', credentials);
    return {
      user_id: response.data.user_id,
      otp_channel: response.data.otp_channel,
      message: response.message,
    };
  };

  const verifyOtp = async (userId: number, code: string, otpChannel: string = 'email') => {
    const response = await apiClient.post<VerifyOtpResponse>('/auth/verify-otp', {
      user_id: userId,
      code,
      otp_channel: otpChannel,
    });
    localStorage.setItem('auth_token', response.data.token);
    setUser(response.data.user);
  };

  const logout = () => {
    localStorage.removeItem('auth_token');
    setUser(null);
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        loading,
        login,
        verifyOtp,
        logout,
        isAuthenticated: !!user,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};
