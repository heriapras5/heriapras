<?php

namespace App\Services\Frontend;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FrontendService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepo,
        protected ServiceRepositoryInterface $serviceRepo,
    ) {}

    public function getProjects(int $limit = 0): Collection
    {
        $q = $this->projectRepo->active()->orderBy('created_at', 'desc');
        if ($limit > 0) {
            $q->limit($limit);
        }
        return $q->all();
    }

    public function getProjectBySlug(string $slug)
    {
        return $this->projectRepo->findBySlug($slug);
    }

    public function getRelatedProjects($project, int $limit = 3): Collection
    {
        $category = $project->categories->first();
        if (! $category) {
            return new Collection();
        }

        return $this->projectRepo->getRelated($project->id, $category->id, $limit);
    }

    public function getPrevProject(int $currentId): ?Model
    {
        return $this->projectRepo->getPrevProject($currentId);
    }

    public function getNextProject(int $currentId): ?Model
    {
        return $this->projectRepo->getNextProject($currentId);
    }

    public function getServices(): Collection
    {
        return $this->serviceRepo->active()->orderBy('sort_order')->all();
    }
}
