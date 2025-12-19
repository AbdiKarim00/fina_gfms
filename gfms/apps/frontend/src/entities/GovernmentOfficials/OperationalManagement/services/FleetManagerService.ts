import { apiClient } from '../../../../../../frontend/src/services/api';
import { Vehicle } from '../../../Vehicles/types/Vehicle';
import { Driver } from '../../FieldOperations/types/Driver';

interface AssignmentData {
  vehicleId: number;
  driverId: number;
  startDate: string;
  endDate: string;
  purpose: string;
}

interface MaintenanceData {
  vehicleId: number;
  type: string;
  scheduledDate: string;
  description: string;
  cost: number;
}

interface ApiResponse<T> {
  success: boolean;
  message: string;
  data?: T;
}

interface AssignmentResponse {
  success: boolean;
  message: string;
  data?: {
    assignmentId: number;
    vehicleId: number;
    driverId: number;
    assignedAt: string;
  };
}

interface MaintenanceResponse {
  success: boolean;
  message: string;
  data?: {
    maintenanceId: number;
    vehicleId: number;
    scheduledDate: string;
    status: string;
  };
}

interface StatisticsResponse {
  totalVehicles: number;
  activeVehicles: number;
  maintenanceDue: number;
  assignmentsToday: number;
}

export class FleetManagerService {
  static async getAvailableVehicles(): Promise<Vehicle[]> {
    try {
      const response = await apiClient.get<ApiResponse<Vehicle[]>>('/api/fleet-manager/vehicles/available');
      return response.data || [];
    } catch (error) {
      console.error('Error fetching available vehicles:', error);
      throw error;
    }
  }

  static async getActiveDrivers(): Promise<Driver[]> {
    try {
      const response = await apiClient.get<ApiResponse<Driver[]>>('/api/fleet-manager/drivers/active');
      return response.data || [];
    } catch (error) {
      console.error('Error fetching active drivers:', error);
      throw error;
    }
  }

  static async assignVehicleToDriver(assignmentData: AssignmentData): Promise<AssignmentResponse> {
    try {
      const response = await apiClient.post<AssignmentResponse>('/api/fleet-manager/assign-vehicle', assignmentData);
      return response;
    } catch (error) {
      console.error('Error assigning vehicle to driver:', error);
      throw error;
    }
  }

  static async scheduleMaintenance(maintenanceData: MaintenanceData): Promise<MaintenanceResponse> {
    try {
      const response = await apiClient.post<MaintenanceResponse>('/api/fleet-manager/schedule-maintenance', maintenanceData);
      return response;
    } catch (error) {
      console.error('Error scheduling maintenance:', error);
      throw error;
    }
  }

  static async getFleetStatistics(): Promise<StatisticsResponse> {
    try {
      const response = await apiClient.get<ApiResponse<StatisticsResponse>>('/api/fleet-manager/statistics');
      return response.data || {} as StatisticsResponse;
    } catch (error) {
      console.error('Error fetching fleet statistics:', error);
      throw error;
    }
  }
}