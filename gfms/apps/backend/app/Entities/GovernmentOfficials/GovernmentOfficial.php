<?php

namespace App\Entities\GovernmentOfficials;

/**
 * Base Government Official Entity
 *
 * Abstract base class for all government officials in the fleet management system.
 * Provides common functionality and attributes for all officials.
 */
abstract class GovernmentOfficial
{
    /**
     * The unique identifier for the official
     */
    protected int $id;

    /**
     * The personal number of the official
     */
    protected string $personalNumber;

    /**
     * The name of the official
     */
    protected string $name;

    /**
     * The position/title of the official
     */
    protected string $position;

    /**
     * The job group of the official
     */
    protected string $jobGroup;

    /**
     * The hierarchical level of the official
     */
    protected int $hierarchicalLevel;

    /**
     * The organization the official belongs to
     */
    protected int $organizationId;

    /**
     * Whether the official's account is active
     */
    protected bool $isActive;

    /**
     * Get the official's ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the official's personal number
     */
    public function getPersonalNumber(): string
    {
        return $this->personalNumber;
    }

    /**
     * Get the official's name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the official's position
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Get the official's job group
     */
    public function getJobGroup(): string
    {
        return $this->jobGroup;
    }

    /**
     * Get the official's hierarchical level
     */
    public function getHierarchicalLevel(): int
    {
        return $this->hierarchicalLevel;
    }

    /**
     * Get the official's organization ID
     */
    public function getOrganizationId(): int
    {
        return $this->organizationId;
    }

    /**
     * Check if the official's account is active
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Get the role of this official
     */
    abstract public function getRole(): string;

    /**
     * Get the permissions for this official
     */
    abstract public function getPermissions(): array;
}
