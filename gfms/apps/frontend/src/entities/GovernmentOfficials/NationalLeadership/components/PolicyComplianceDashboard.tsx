import React from 'react';
import { Card, Table, Typography, Progress, Space } from 'antd';
import { PolicyComplianceData } from '../services/CabinetSecretaryService';

const { Title, Text } = Typography;

interface Props {
  data?: PolicyComplianceData;
  loading: boolean;
}

export const PolicyComplianceDashboard: React.FC<Props> = ({ data, loading }) => {
  const columns = [
    {
      title: 'Department',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Compliance Rate',
      dataIndex: 'compliance',
      key: 'compliance',
      render: (compliance: number) => (
        <Progress percent={compliance} size="small" status={compliance < 80 ? 'exception' : 'active'} />
      ),
    },
  ];

  return (
    <Card loading={loading} title={<Title level={4}>Policy Compliance Oversight</Title>}>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <Card.Grid className="w-full text-center">
          <Text type="secondary">Overall Rate</Text>
          <div className="text-2xl font-bold">{data?.compliance_rate}%</div>
        </Card.Grid>
        <Card.Grid className="w-full text-center">
          <Text type="secondary">Active Violations</Text>
          <div className="text-2xl font-bold text-red-500">{data?.violations}</div>
        </Card.Grid>
        <Card.Grid className="w-full text-center">
          <Text type="secondary">Pending Audits</Text>
          <div className="text-2xl font-bold text-orange-500">{data?.pending_audits}</div>
        </Card.Grid>
      </div>

      <Table
        dataSource={data?.department_breakdown}
        columns={columns}
        pagination={false}
        rowKey="name"
        size="small"
      />
    </Card>
  );
};
