import React from 'react';
import { useAuth } from '../contexts/AuthContext';

export const DashboardPage: React.FC = () => {
  const { user } = useAuth();

  return (
    <div className="px-4 py-6 sm:px-0">
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p className="mt-1 text-sm text-gray-600">Welcome back, {user?.name}!</p>
      </div>

      <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        {/* Stats Cards */}
        <div className="bg-white overflow-hidden shadow rounded-lg">
          <div className="p-5">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div className="ml-5 w-0 flex-1">
                <dl>
                  <dt className="text-sm font-medium text-gray-500 truncate">Total Vehicles</dt>
                  <dd className="text-lg font-medium text-gray-900">2</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-white overflow-hidden shadow rounded-lg">
          <div className="p-5">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <div className="ml-5 w-0 flex-1">
                <dl>
                  <dt className="text-sm font-medium text-gray-500 truncate">Active Drivers</dt>
                  <dd className="text-lg font-medium text-gray-900">1</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-white overflow-hidden shadow rounded-lg">
          <div className="p-5">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <div className="ml-5 w-0 flex-1">
                <dl>
                  <dt className="text-sm font-medium text-gray-500 truncate">Pending Bookings</dt>
                  <dd className="text-lg font-medium text-gray-900">0</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="mt-8">
        <h2 className="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <button className="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow text-left">
            <h3 className="font-medium text-gray-900">Add Vehicle</h3>
            <p className="text-sm text-gray-500 mt-1">Register a new vehicle</p>
          </button>
          <button className="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow text-left">
            <h3 className="font-medium text-gray-900">Book Vehicle</h3>
            <p className="text-sm text-gray-500 mt-1">Create a new booking</p>
          </button>
          <button className="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow text-left">
            <h3 className="font-medium text-gray-900">Add Driver</h3>
            <p className="text-sm text-gray-500 mt-1">Register a new driver</p>
          </button>
          <button className="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow text-left">
            <h3 className="font-medium text-gray-900">View Reports</h3>
            <p className="text-sm text-gray-500 mt-1">Generate fleet reports</p>
          </button>
        </div>
      </div>
    </div>
  );
};
