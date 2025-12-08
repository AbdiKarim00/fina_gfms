import React, { useEffect, useState } from 'react';
import { apiClient } from '../services/api';
import { Vehicle } from '../types';

export const VehiclesPage: React.FC = () => {
  const [vehicles, setVehicles] = useState<Vehicle[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchVehicles();
  }, []);

  const fetchVehicles = async () => {
    try {
      const data = await apiClient.get<{ data: Vehicle[] }>('/vehicles');
      setVehicles(data.data || []);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to fetch vehicles');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="rounded-md bg-red-50 p-4">
        <p className="text-sm text-red-800">{error}</p>
      </div>
    );
  }

  return (
    <div className="px-4 py-6 sm:px-0">
      <div className="sm:flex sm:items-center">
        <div className="sm:flex-auto">
          <h1 className="text-2xl font-bold text-gray-900">Vehicles</h1>
          <p className="mt-2 text-sm text-gray-700">
            A list of all vehicles in the fleet including their registration, make, model, and status.
          </p>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            className="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
          >
            Add Vehicle
          </button>
        </div>
      </div>

      <div className="mt-8 flex flex-col">
        <div className="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div className="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table className="min-w-full divide-y divide-gray-300">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                      Registration
                    </th>
                    <th className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Make & Model
                    </th>
                    <th className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Year
                    </th>
                    <th className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Fuel Type
                    </th>
                    <th className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Status
                    </th>
                    <th className="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span className="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200 bg-white">
                  {vehicles.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="py-8 text-center text-sm text-gray-500">
                        No vehicles found. Add your first vehicle to get started.
                      </td>
                    </tr>
                  ) : (
                    vehicles.map((vehicle) => (
                      <tr key={vehicle.id}>
                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                          {vehicle.registration_number}
                        </td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {vehicle.make} {vehicle.model}
                        </td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {vehicle.year}
                        </td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {vehicle.fuel_type}
                        </td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <span
                            className={`inline-flex rounded-full px-2 text-xs font-semibold leading-5 ${
                              vehicle.status === 'active'
                                ? 'bg-green-100 text-green-800'
                                : vehicle.status === 'maintenance'
                                ? 'bg-yellow-100 text-yellow-800'
                                : 'bg-gray-100 text-gray-800'
                            }`}
                          >
                            {vehicle.status}
                          </span>
                        </td>
                        <td className="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                          <button className="text-blue-600 hover:text-blue-900">
                            View
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
