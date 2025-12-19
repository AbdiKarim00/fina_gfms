import React from 'react';
import { Row, Col, Typography, Space } from 'antd';
import { useQuery } from '@tanstack/react-query';
import { cabinetSecretaryService } from '../services/CabinetSecretaryService';
import { PolicyComplianceDashboard } from './PolicyComplianceDashboard';
import { BudgetOversightDashboard } from './BudgetOversightDashboard';
import { StrategicPerformanceDashboard } from './StrategicPerformanceDashboard';
import { GovernanceInterventionForm } from './GovernanceInterventionForm';

const { Title, Paragraph } = Typography;

export const CabinetSecretaryDashboard: React.FC = () => {
  const policyQuery = useQuery({
    queryKey: ['policy-compliance'],
    queryFn: () => cabinetSecretaryService.getPolicyCompliance(),
  });

  const budgetQuery = useQuery({
    queryKey: ['budget-oversight'],
    queryFn: () => cabinetSecretaryService.getBudgetOversight(),
  });

  const strategicQuery = useQuery({
    queryKey: ['strategic-performance'],
    queryFn: () => cabinetSecretaryService.getStrategicPerformance(),
  });

  return (
    <div className="p-6">
      <header className="mb-8">
        <Title>Cabinet Secretary Oversight Portal</Title>
        <Paragraph className="text-lg text-gray-600">
          Strategic monitoring and governance intervention for the National Fleet Management System.
        </Paragraph>
      </header>

      <Space direction="vertical" size="large" className="w-full">
        <Row gutter={[24, 24]}>
          <Col xs={24} lg={16}>
            <PolicyComplianceDashboard 
              data={policyQuery.data} 
              loading={policyQuery.isLoading} 
            />
          </Col>
          <Col xs={24} lg={8}>
            <StrategicPerformanceDashboard 
              data={strategicQuery.data} 
              loading={strategicQuery.isLoading} 
            />
          </Col>
        </Row>

        <Row gutter={[24, 24]}>
          <Col xs={24} lg={12}>
            <BudgetOversightDashboard 
              data={budgetQuery.data} 
              loading={budgetQuery.isLoading} 
            />
          </Col>
          <Col xs={24} lg={12}>
            <GovernanceInterventionForm />
          </Col>
        </Row>
      </Space>
    </div>
  );
};
