<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentUser
 * @package App\Repositories\User
 */
class EloquentUser extends AbstractEloquentRepository implements UserInterface
{
    /**
     * Create a new repository instance.
     *
     * @param Model $user
     * @return void
     */
    public function __construct(Model $user)
    {
        $this->model = $user;
    }

    /**
     * Get paginated users.
     *
     * @param int $page  Page number
     * @param int $limit Number of users per page
     * @return LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        return $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }
}
