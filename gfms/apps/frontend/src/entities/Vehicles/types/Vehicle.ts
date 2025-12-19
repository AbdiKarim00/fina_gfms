export interface Vehicle {
  id: number;
  registrationNumber: string;
  make: string;
  model: string;
  year: number;
  color: string;
  vin: string | null;
  engineNumber: string | null;
  fuelType: string;
  fuelConsumptionRate: number | null;
  purchaseDate: string | null;
  purchasePrice: number | null;
  status: 'active' | 'inactive' | 'maintenance' | 'disposed';
  notes: string | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}