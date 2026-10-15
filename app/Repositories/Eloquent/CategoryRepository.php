<?php

namespace App\Repositories\Eloquent;

use App\Models\ProjectCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected function makeModel(): ProjectCategory
    {
        return new ProjectCategory();
    }
}
