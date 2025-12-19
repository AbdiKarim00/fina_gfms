import React, { useState, useEffect } from 'react';
import { FleetManagerService } from '../services/FleetManagerService';
import { Vehicle } from '../../../Vehicles/types/Vehicle';
import { Driver } from '../../../GovernmentOfficials/FieldOperations/types/Driver';

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

const FleetManagerDashboard: React.FC = () => {
  const [vehicles, setVehicles] = useState<Vehicle[]>([]);
  const [drivers, setDrivers] = useState<Driver[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const [vehiclesData, driversData] = await Promise.all([
          FleetManagerService.getAvailableVehicles(),
          FleetManagerService.getActiveDrivers()
        ]);
        setVehicles(vehiclesData);
        setDrivers(driversData);
      } catch (err) {
        setError('Failed to load data');
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  const handleAssignVehicle = async (assignmentData: AssignmentData) => {
    try {
      const result = await FleetManagerService.assignVehicleToDriver(assignmentData);
      if (result.success) {
        alert('Vehicle assigned successfully');
        // Refresh the data
        window.location.reload();
      } else {
        alert('Failed to assign vehicle: ' + result.message);
      }
    } catch (err) {
      alert('Error assigning vehicle');
      console.error(err);
    }
  };

  const handleScheduleMaintenance = async (maintenanceData: MaintenanceData) => {
    try {
      const result = await FleetManagerService.scheduleMaintenance(maintenanceData);
      if (result.success) {
        alert('Maintenance scheduled successfully');
        // Refresh the data
        window.location.reload();
      } else {
        alert('Failed to schedule maintenance: ' + result.message);
      }
    } catch (err) {
      alert('Error scheduling maintenance');
      console.error(err);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="fleet-manager-dashboard">
      <h1>Fleet Manager Dashboard</h1>
      
      <section className="vehicle-assignment">
        <h2>Assign Vehicle to Driver</h2>
        <form onSubmit={(e) => {
          e.preventDefault();
          const formData = new FormData(e.target as HTMLFormElement);
          const assignmentData: AssignmentData = {
            vehicleId: parseInt(formData.get('vehicleId') as string),
            driverId: parseInt(formData.get('driverId') as string),
            startDate: formData.get('startDate') as string,
            endDate: formData.get('endDate') as string,
            purpose: formData.get('purpose') as string
          };
          handleAssignVehicle(assignmentData);
        }}>
          <div>
            <label htmlFor="vehicleId">Vehicle:</label>
            <select name="vehicleId" required>
              <option value="">Select a vehicle</option>
              {vehicles.map(vehicle => (
                <option key={vehicle.id} value={vehicle.id}>
                  {vehicle.make} {vehicle.model} ({vehicle.registrationNumber})
                </option>
              ))}
            </select>
          </div>
          
          <div>
            <label htmlFor="driverId">Driver:</label>
            <select name="driverId" required>
              <option value="">Select a driver</option>
              {drivers.map(driver => (
                <option key={driver.id} value={driver.id}>
                  {driver.name} ({driver.licenseNumber})
                </option>
              ))}
            </select>
          </div>
          
          <div>
            <label htmlFor="startDate">Start Date:</label>
            <input type="date" name="startDate" required />
          </div>
          
          <div>
            <label htmlFor="endDate">End Date:</label>
            <input type="date" name="endDate" required />
          </div>
          
          <div>
            <label htmlFor="purpose">Purpose:</label>
            <textarea name="purpose" required />
          </div>
          
          <button type="submit">Assign Vehicle</button>
        </form>
      </section>
      
      <section className="maintenance-scheduling">
        <h2>Schedule Vehicle Maintenance</h2>
        <form onSubmit={(e) => {
          e.preventDefault();
          const formData = new FormData(e.target as HTMLFormElement);
          const maintenanceData: MaintenanceData = {
            vehicleId: parseInt(formData.get('maintenanceVehicleId') as string),
            type: formData.get('type') as string,
            scheduledDate: formData.get('scheduledDate') as string,
            description: formData.get('maintenanceDescription') as string,
            cost: parseFloat(formData.get('cost') as string)
          };
          handleScheduleMaintenance(maintenanceData);
        }}>
          <div>
            <label htmlFor="maintenanceVehicleId">Vehicle:</label>
            <select name="maintenanceVehicleId" required>
              <option value="">Select a vehicle</option>
              {vehicles.map(vehicle => (
                <option key={vehicle.id} value={vehicle.id}>
                  {vehicle.make} {vehicle.model} ({vehicle.registrationNumber})
                </option>
              ))}
            </select>
          </div>
          
          <div>
            <label htmlFor="type">Maintenance Type:</label>
            <select name="type" required>
              <option value="">Select type</option>
              <option value="routine">Routine Service</option>
              <option value="repair">Repair</option>
              <option value="inspection">Inspection</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div>
            <label htmlFor="scheduledDate">Scheduled Date:</label>
            <input type="date" name="scheduledDate" required />
          </div>
          
          <div>
            <label htmlFor="maintenanceDescription">Description:</label>
            <textarea name="maintenanceDescription" required />
          </div>
          
          <div>
            <label htmlFor="cost">Estimated Cost (KES):</label>
            <input type="number" name="cost" step="0.01" min="0" required />
          </div>
          
          <button type="submit">Schedule Maintenance</button>
        </form>
      </section>
    </div>
  );
};

export default FleetManagerDashboard;