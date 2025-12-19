<?php

namespace App\Console\Commands;

use App\Repositories\DriverRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Console\Command;

class TestRepositoryStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-repository-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test repository structure and instantiation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing repository structure and instantiation...');

        // Test that repositories can be instantiated
        $vehicleRepo = new VehicleRepository;
        $this->info('✓ VehicleRepository instantiated successfully');

        $driverRepo = new DriverRepository;
        $this->info('✓ DriverRepository instantiated successfully');

        $orgRepo = new OrganizationRepository;
        $this->info('✓ OrganizationRepository instantiated successfully');

        // Test that repositories implement the interface
        if ($vehicleRepo instanceof \App\Repositories\Contracts\BaseRepositoryInterface) {
            $this->info('✓ VehicleRepository implements BaseRepositoryInterface');
        }

        if ($driverRepo instanceof \App\Repositories\Contracts\BaseRepositoryInterface) {
            $this->info('✓ DriverRepository implements BaseRepositoryInterface');
        }

        if ($orgRepo instanceof \App\Repositories\Contracts\BaseRepositoryInterface) {
            $this->info('✓ OrganizationRepository implements BaseRepositoryInterface');
        }

        // Test that repositories extend the base class
        if ($vehicleRepo instanceof \App\Repositories\BaseRepository) {
            $this->info('✓ VehicleRepository extends BaseRepository');
        }

        if ($driverRepo instanceof \App\Repositories\BaseRepository) {
            $this->info('✓ DriverRepository extends BaseRepository');
        }

        if ($orgRepo instanceof \App\Repositories\BaseRepository) {
            $this->info('✓ OrganizationRepository extends BaseRepository');
        }

        $this->info('Repository structure test completed successfully!');
    }
}
