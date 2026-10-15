<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected array $criteria = [];

    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    abstract protected function makeModel(): Model;

    public function all(array $columns = ['*']): Collection
    {
        return $this->applyCriteria()->get($columns);
    }

    public function paginate(int $perPage = 10, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->applyCriteria()->paginate($perPage, $columns);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->find($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->find($id)?->delete() ?? false;
    }

    public function where(string $column, $value, string $operator = '='): Collection
    {
        return $this->model->where($column, $operator, $value)->get();
    }

    public function whereIn(string $column, array $values): Collection
    {
        return $this->model->whereIn($column, $values)->get();
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->criteria[] = fn($q) => $q->orderBy($column, $direction);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->criteria[] = fn($q) => $q->limit($limit);
        return $this;
    }

    public function pluck(string $column): array
    {
        return $this->applyCriteria()->pluck($column)->toArray();
    }

    public function active(): self
    {
        $this->criteria[] = fn($q) => $q->where('status', true);
        return $this;
    }

    public function featured(): self
    {
        $this->criteria[] = fn($q) => $q->where('is_featured', true);
        return $this;
    }

    protected function applyCriteria()
    {
        $query = $this->model->newQuery();
        foreach ($this->criteria as $criterion) {
            $criterion($query);
        }
        $this->criteria = [];
        return $query;
    }
}
