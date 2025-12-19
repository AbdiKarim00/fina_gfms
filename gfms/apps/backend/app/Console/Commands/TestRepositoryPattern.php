<?php

namespace App\Console\Commands;

use App\Repositories\VehicleRepository;
use Illuminate\Console\Command;

class TestRepositoryPattern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-repository-pattern';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test repository pattern implementation';

    /**
     * Execute the console command.
     */
    public function handle(VehicleRepository $vehicleRepository)
    {
        $this->info('Testing repository pattern implementation...');

        // Test creating a vehicle
        $vehicleData = [
            'registration_number' => 'KBA 123A',
            'make' => 'Toyota',
            'model' => 'Land Cruiser',
            'year' => 2020,
            'fuel_type' => 'petrol',
            'status' => 'active',
        ];

        $vehicle = $vehicleRepository->create($vehicleData);
        $this->info('Created vehicle: '.$vehicle->registration_number);

        // Test finding a vehicle
        $foundVehicle = $vehicleRepository->find($vehicle->id);
        $this->info('Found vehicle: '.$foundVehicle->registration_number);

        // Test finding by registration number
        $vehicleByReg = $vehicleRepository->findByRegistrationNumber('KBA 123A');
        $this->info('Found vehicle by registration: '.$vehicleByReg->registration_number);

        // Test getting all vehicles
        $allVehicles = $vehicleRepository->all();
        $this->info('Total vehicles: '.$allVehicles->count());

        // Test updating a vehicle
        $updatedVehicle = $vehicleRepository->update($vehicle->id, [
            'color' => 'White',
            'status' => 'maintenance',
        ]);
        $this->info('Updated vehicle color to: '.$updatedVehicle->color);
        $this->info('Updated vehicle status to: '.$updatedVehicle->status);

        // Test deleting a vehicle
        $deleted = $vehicleRepository->delete($vehicle->id);
        $this->info('Vehicle deleted: '.($deleted ? 'Yes' : 'No'));

        $this->info('Repository pattern test completed successfully!');
    }
}
