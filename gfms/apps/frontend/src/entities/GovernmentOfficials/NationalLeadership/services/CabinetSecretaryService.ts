import { apiClient } from '../../../../services/api';

export interface PolicyComplianceData {
  compliance_rate: number;
  violations: number;
  pending_audits: number;
  department_breakdown: Array<{ name: string; compliance: number }>;
}

export interface BudgetOversightData {
  total_budget: number;
  executed_budget: number;
  utilization_rate: number;
  monthly_expenditure: Array<{ month: string; amount: number }>;
}

export interface StrategicPerformanceData {
  kpis: Array<{ label: string; value: string }>;
}

class CabinetSecretaryService {
  async getPolicyCompliance(): Promise<PolicyComplianceData> {
    const response = await apiClient.get<{ status: string; data: PolicyComplianceData }>('/cabinet-secretary/policy-compliance');
    return response.data;
  }

  async getBudgetOversight(): Promise<BudgetOversightData> {
    const response = await apiClient.get<{ status: string; data: BudgetOversightData }>('/cabinet-secretary/budget-oversight');
    return response.data;
  }

  async getStrategicPerformance(): Promise<StrategicPerformanceData> {
    const response = await apiClient.get<{ status: string; data: StrategicPerformanceData }>('/cabinet-secretary/strategic-performance');
    return response.data;
  }

  async postIntervention(data: { workflow_id: string; action: string; reason: string }): Promise<void> {
    await apiClient.post('/cabinet-secretary/interventions', data);
  }
}

export const cabinetSecretaryService = new CabinetSecretaryService();
