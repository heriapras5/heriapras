<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 10, array $columns = ['*']): LengthAwarePaginator;
    public function find(int $id): ?Model;
    public function findBySlug(string $slug): ?Model;
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
    public function delete(int $id): bool;
    public function where(string $column, $value, string $operator = '='): Collection;
    public function whereIn(string $column, array $values): Collection;
    public function orderBy(string $column, string $direction = 'asc'): self;
    public function limit(int $limit): self;
    public function active(): self;
    public function featured(): self;
    public function pluck(string $column): array;
}
