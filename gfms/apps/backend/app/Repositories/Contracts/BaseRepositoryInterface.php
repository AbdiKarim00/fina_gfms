<?php

namespace App\Repositories\Contracts;

/**
 * Base repository interface defining common CRUD operations
 * Following SOLID principles, specifically Interface Segregation Principle
 */
interface BaseRepositoryInterface
{
    /**
     * Find a record by ID
     */
    public function find(string $id);

    /**
     * Find a record by ID with relationships
     */
    public function findWith(array $relations, string $id);

    /**
     * Get all records
     */
    public function all();

    /**
     * Get paginated records
     */
    public function paginate(int $limit = 15);

    /**
     * Create a new record
     */
    public function create(array $data);

    /**
     * Update an existing record
     */
    public function update(string $id, array $data);

    /**
     * Delete a record
     */
    public function delete(string $id);

    /**
     * Find records by criteria
     */
    public function findBy(array $criteria);

    /**
     * Find first record by criteria
     */
    public function findOneBy(array $criteria);
}
