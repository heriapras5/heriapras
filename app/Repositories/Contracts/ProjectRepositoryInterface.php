<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    public function findByCategory(int $categoryId);
    public function getRelated(int $projectId, int $categoryId, int $limit = 3);
    public function search(string $term);
    public function getPrevProject(int $currentId): ?Model;
    public function getNextProject(int $currentId): ?Model;
}
