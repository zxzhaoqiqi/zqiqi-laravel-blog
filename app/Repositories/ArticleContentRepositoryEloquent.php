<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ArticleContentRepository;
use App\Models\ArticleContent;
use App\Validators\ArticleContentValidator;

/**
 * Class ArticleContentRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ArticleContentRepositoryEloquent extends BaseRepository implements ArticleContentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ArticleContent::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
