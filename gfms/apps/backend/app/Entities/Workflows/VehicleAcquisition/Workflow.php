<?php

namespace App\Entities\Workflows\VehicleAcquisition;

/**
 * Vehicle Acquisition Workflow
 *
 * Implements the vehicle acquisition workflow as defined in the Government Transport Policy (2024).
 *
 * Workflow Steps:
 * 1. Initiation: Department Head identifies need
 * 2. Approval: Accounting Officer reviews budget
 * 3. Authorization: GFMD approves allocation
 * 4. Procurement: Supplies Branch executes purchase
 * 5. Registration: NTSA registers vehicle
 * 6. Assignment: Fleet Manager assigns to user
 */
class Workflow
{
    /**
     * The unique identifier for the workflow instance
     */
    protected int $id;

    /**
     * The department initiating the request
     */
    protected int $initiatingDepartmentId;

    /**
     * The official initiating the request
     */
    protected int $initiatorId;

    /**
     * The type of vehicle requested
     */
    protected string $vehicleType;

    /**
     * The purpose of the vehicle acquisition
     */
    protected string $purpose;

    /**
     * The estimated cost
     */
    protected float $estimatedCost;

    /**
     * The current step in the workflow
     */
    protected string $currentStep;

    /**
     * The status of the workflow
     */
    protected string $status;

    /**
     * Timestamps
     */
    protected string $createdAt;

    protected ?string $updatedAt;

    protected ?string $completedAt;

    /**
     * Workflow steps
     */
    public const STEP_INITIATION = 'initiation';

    public const STEP_APPROVAL = 'approval';

    public const STEP_AUTHORIZATION = 'authorization';

    public const STEP_PROCUREMENT = 'procurement';

    public const STEP_REGISTRATION = 'registration';

    public const STEP_ASSIGNMENT = 'assignment';

    /**
     * Workflow statuses
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_REJECTED = 'rejected';

    /**
     * Initialize a new vehicle acquisition workflow
     */
    public function __construct(
        int $initiatingDepartmentId,
        int $initiatorId,
        string $vehicleType,
        string $purpose,
        float $estimatedCost
    ) {
        $this->initiatingDepartmentId = $initiatingDepartmentId;
        $this->initiatorId = $initiatorId;
        $this->vehicleType = $vehicleType;
        $this->purpose = $purpose;
        $this->estimatedCost = $estimatedCost;
        $this->currentStep = self::STEP_INITIATION;
        $this->status = self::STATUS_PENDING;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    /**
     * Move to the next step in the workflow
     */
    public function moveToNextStep(): bool
    {
        $steps = [
            self::STEP_INITIATION,
            self::STEP_APPROVAL,
            self::STEP_AUTHORIZATION,
            self::STEP_PROCUREMENT,
            self::STEP_REGISTRATION,
            self::STEP_ASSIGNMENT,
        ];

        $currentIndex = array_search($this->currentStep, $steps);

        if ($currentIndex === false || $currentIndex === count($steps) - 1) {
            return false;
        }

        $this->currentStep = $steps[$currentIndex + 1];
        $this->updatedAt = date('Y-m-d H:i:s');

        if ($this->currentStep === self::STEP_ASSIGNMENT) {
            $this->status = self::STATUS_COMPLETED;
            $this->completedAt = date('Y-m-d H:i:s');
        }

        return true;
    }

    /**
     * Reject the workflow
     */
    public function reject(string $reason): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->updatedAt = date('Y-m-d H:i:s');
        // In a real implementation, we would log the rejection reason
    }

    /**
     * Get the current step
     */
    public function getCurrentStep(): string
    {
        return $this->currentStep;
    }

    /**
     * Get the workflow status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Check if the workflow is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
