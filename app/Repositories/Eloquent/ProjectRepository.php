<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    protected function makeModel(): Project
    {
        return new Project();
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->criteria[] = fn($q) => $q->with(['images', 'categories']);
        return parent::all($columns);
    }

    public function paginate(int $perPage = 10, array $columns = ['*']): LengthAwarePaginator
    {
        $this->criteria[] = fn($q) => $q->with(['images', 'categories']);
        return parent::paginate($perPage, $columns);
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model->with(['images', 'categories', 'features', 'technologies', 'results', 'testimonial'])->where('slug', $slug)->first();
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->model
            ->with('categories')
            ->whereHas('categories', fn($q) => $q->where('id', $categoryId))
            ->where('status', true)
            ->get();
    }

    public function getRelated(int $projectId, int $categoryId, int $limit = 3): Collection
    {
        return $this->model
            ->with(['images', 'categories'])
            ->where('id', '!=', $projectId)
            ->whereHas('categories', fn($q) => $q->where('id', $categoryId))
            ->where('status', true)
            ->limit($limit)
            ->get();
    }

    public function search(string $term): Collection
    {
        return $this->model
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('client', 'like', "%{$term}%");
            })
            ->where('status', true)
            ->get();
    }

    public function getPrevProject(int $currentId): ?Model
    {
        $ids = $this->model->where('status', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->pluck('id')
            ->toArray();
        $index = array_search($currentId, $ids);
        if ($index === false || $index >= count($ids) - 1) {
            return null;
        }
        return $this->model->with(['images', 'categories'])->find($ids[$index + 1]);
    }

    public function getNextProject(int $currentId): ?Model
    {
        $ids = $this->model->where('status', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->pluck('id')
            ->toArray();
        $index = array_search($currentId, $ids);
        if ($index === false || $index <= 0) {
            return null;
        }
        return $this->model->with(['images', 'categories'])->find($ids[$index - 1]);
    }
}
