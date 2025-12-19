export interface Driver {
  id: number;
  userId: number;
  name: string;
  licenseNumber: string;
  licenseExpiryDate: string;
  licenseClass: string;
  dateHired: string | null;
  status: 'active' | 'inactive' | 'suspended' | 'on_leave';
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}