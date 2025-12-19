<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Base repository class implementing common CRUD operations
 * Following SOLID principles:
 * - Single Responsibility Principle: Each method has one reason to change
 * - Open/Closed Principle: Open for extension, closed for modification
 * - Dependency Inversion Principle: Depends on Model abstraction
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The model instance
     */
    protected Model $model;

    /**
     * Get the model instance
     */
    abstract protected function getModel(): Model;

    /**
     * Find a record by ID
     */
    public function find(string $id)
    {
        return $this->getModel()::find($id);
    }

    /**
     * Find a record by ID with relationships
     */
    public function findWith(array $relations, string $id)
    {
        return $this->getModel()::with($relations)->find($id);
    }

    /**
     * Get all records
     */
    public function all()
    {
        return $this->getModel()::all();
    }

    /**
     * Get paginated records
     */
    public function paginate(int $limit = 15)
    {
        return $this->getModel()::paginate($limit);
    }

    /**
     * Create a new record
     */
    public function create(array $data)
    {
        return $this->getModel()::create($data);
    }

    /**
     * Update an existing record
     */
    public function update(string $id, array $data)
    {
        $record = $this->find($id);
        if ($record) {
            $record->update($data);

            return $record;
        }

        return null;
    }

    /**
     * Delete a record
     */
    public function delete(string $id): bool
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }

        return false;
    }

    /**
     * Find records by criteria
     */
    public function findBy(array $criteria)
    {
        $query = $this->getModel()::query();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get();
    }

    /**
     * Find first record by criteria
     */
    public function findOneBy(array $criteria)
    {
        $query = $this->getModel()::query();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->first();
    }
}
