import React from 'react';
import { Card, Typography, List, Tag } from 'antd';
import { StrategicPerformanceData } from '../services/CabinetSecretaryService';

const { Title } = Typography;

interface Props {
  data?: StrategicPerformanceData;
  loading: boolean;
}

export const StrategicPerformanceDashboard: React.FC<Props> = ({ data, loading }) => {
  return (
    <Card loading={loading} title={<Title level={4}>Strategic Performance Indicators</Title>}>
      <List
        dataSource={data?.kpis}
        renderItem={(item) => (
          <List.Item>
            <List.Item.Meta
              title={item.label}
            />
            <Tag color="blue" className="text-lg px-3 py-1">
              {item.value}
            </Tag>
          </List.Item>
        )}
      />
    </Card>
  );
};
