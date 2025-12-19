<?php

namespace App\Entities\Vehicles;

/**
 * Vehicle Entity
 *
 * Represents a physical vehicle asset managed by the fleet management system.
 * As defined in the Government Transport Policy (2024).
 */
class Vehicle
{
    /**
     * The unique identifier for the vehicle
     */
    protected int $id;

    /**
     * The registration number of the vehicle
     */
    protected string $registrationNumber;

    /**
     * The make of the vehicle
     */
    protected string $make;

    /**
     * The model of the vehicle
     */
    protected string $model;

    /**
     * The year of manufacture
     */
    protected int $year;

    /**
     * The color of the vehicle
     */
    protected string $color;

    /**
     * The VIN (Vehicle Identification Number)
     */
    protected ?string $vin;

    /**
     * The engine number
     */
    protected ?string $engineNumber;

    /**
     * The fuel type of the vehicle
     */
    protected string $fuelType;

    /**
     * The fuel consumption rate
     */
    protected ?float $fuelConsumptionRate;

    /**
     * The purchase date
     */
    protected ?string $purchaseDate;

    /**
     * The purchase price
     */
    protected ?float $purchasePrice;

    /**
     * The status of the vehicle
     */
    protected string $status;

    /**
     * Notes about the vehicle
     */
    protected ?string $notes;

    /**
     * Get the vehicle's ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the vehicle's registration number
     */
    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    /**
     * Get the vehicle's make
     */
    public function getMake(): string
    {
        return $this->make;
    }

    /**
     * Get the vehicle's model
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Get the vehicle's year of manufacture
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Get the vehicle's color
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Get the vehicle's VIN
     */
    public function getVin(): ?string
    {
        return $this->vin;
    }

    /**
     * Get the vehicle's engine number
     */
    public function getEngineNumber(): ?string
    {
        return $this->engineNumber;
    }

    /**
     * Get the vehicle's fuel type
     */
    public function getFuelType(): string
    {
        return $this->fuelType;
    }

    /**
     * Get the vehicle's fuel consumption rate
     */
    public function getFuelConsumptionRate(): ?float
    {
        return $this->fuelConsumptionRate;
    }

    /**
     * Get the vehicle's purchase date
     */
    public function getPurchaseDate(): ?string
    {
        return $this->purchaseDate;
    }

    /**
     * Get the vehicle's purchase price
     */
    public function getPurchasePrice(): ?float
    {
        return $this->purchasePrice;
    }

    /**
     * Get the vehicle's status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get notes about the vehicle
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * Check if the vehicle is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
