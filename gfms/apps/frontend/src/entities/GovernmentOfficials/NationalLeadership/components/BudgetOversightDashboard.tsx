import React from 'react';
import { Card, Typography, Statistic, Row, Col } from 'antd';
import { BudgetOversightData } from '../services/CabinetSecretaryService';

const { Title } = Typography;

interface Props {
  data?: BudgetOversightData;
  loading: boolean;
}

export const BudgetOversightDashboard: React.FC<Props> = ({ data, loading }) => {
  return (
    <Card loading={loading} title={<Title level={4}>Budget Execution Oversight</Title>}>
      <Row gutter={[16, 16]}>
        <Col span={12}>
          <Statistic
            title="Total Budget"
            value={data?.total_budget}
            prefix="M"
            precision={2}
          />
        </Col>
        <Col span={12}>
          <Statistic
            title="Execution Rate"
            value={data?.utilization_rate}
            suffix="%"
            valueStyle={{ color: (data?.utilization_rate || 0) > 90 ? '#cf1322' : '#3f8600' }}
          />
        </Col>
        <Col span={24}>
          <Statistic
            title="Executed Budget"
            value={data?.executed_budget}
            prefix="M"
            precision={2}
          />
        </Col>
      </Row>
    </Card>
  );
};
