<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PageVariablesRepository;
use App\Models\PageVariables;
use App\Validators\PageVariablesValidator;

/**
 * Class PageVariablesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PageVariablesRepositoryEloquent extends BaseRepository implements PageVariablesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PageVariables::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
