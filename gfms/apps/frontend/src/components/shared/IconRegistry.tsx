import {
  DashboardOutlined,
  CarOutlined,
  UserOutlined,
  SettingOutlined,
  FileTextOutlined,
  ToolOutlined,
  DashboardFilled,
  PlusOutlined,
  EditOutlined,
  DeleteOutlined,
  EyeOutlined,
} from '@ant-design/icons';

export const Icons = {
  dashboard: DashboardOutlined,
  car: CarOutlined,
  user: UserOutlined,
  settings: SettingOutlined,
  file: FileTextOutlined,
  tool: ToolOutlined,
  fuel: DashboardFilled,
  plus: PlusOutlined,
  edit: EditOutlined,
  delete: DeleteOutlined,
  eye: EyeOutlined,
};

export type IconName = keyof typeof Icons;
