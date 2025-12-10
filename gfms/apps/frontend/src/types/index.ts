export interface User {
  id: number;
  name: string;
  email: string;
  personal_number?: string;
  phone?: string;
  id_number?: string;
  email_verified_at?: string;
  roles?: string[];
  permissions?: string[];
  organization?: any;
  last_login_at?: string;
  created_at?: string;
  updated_at?: string;
}

export interface Vehicle {
  id: number;
  registration_number: string;
  make: string;
  model: string;
  year: number;
  fuel_type: string;
  status: 'active' | 'inactive' | 'maintenance' | 'disposed';
  // Additional fields from real fleet register
  engine_number?: string;
  chassis_number?: string;
  color?: string;
  mileage?: number;
  capacity?: number;
  purchase_year?: number;
  current_location?: string;
  original_location?: string;
  responsible_officer?: string;
  has_log_book?: boolean;
  notes?: string;
  organization_id?: number;
  created_at: string;
  updated_at: string;
}

export interface LoginResponse {
  success: boolean;
  message: string;
  data: {
    user_id: number;
    otp_channel: 'email' | 'sms';
  };
}

export interface VerifyOtpResponse {
  success: boolean;
  message: string;
  data: {
    token: string;
    user: User & {
      roles: string[];
      permissions: string[];
      organization?: any;
    };
  };
}

export interface AuthResponse {
  user: User;
  token: string;
}

export interface LoginCredentials {
  personal_number: string;
  password: string;
  otp_channel?: 'email' | 'sms';
}

export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}

export interface Booking {
  id: number;
  vehicle_id: number;
  requester_id: number;
  driver_id?: number;
  start_date: string;
  end_date: string;
  purpose: string;
  destination: string;
  passengers: number;
  status: 'pending' | 'approved' | 'rejected' | 'completed' | 'cancelled';
  priority: 'high' | 'medium' | 'low';
  approved_by?: number;
  approved_at?: string;
  rejection_reason?: string;
  notes?: string;
  created_at: string;
  updated_at: string;
  // Relationships
  vehicle?: Vehicle;
  requester?: User;
  driver?: User;
  approver?: User;
}

export interface BookingFormData {
  vehicle_id: number;
  driver_id?: number;
  start_date: string;
  end_date: string;
  purpose: string;
  destination: string;
  passengers: number;
  priority?: 'high' | 'medium' | 'low';
  notes?: string;
}

export interface BookingFilters {
  status?: string;
  priority?: string;
  vehicle_id?: number;
  start_date?: string;
  end_date?: string;
}

export interface BookingConflict {
  id: number;
  vehicle_id: number;
  start_date: string;
  end_date: string;
  requester?: User;
}
