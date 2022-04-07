<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-03
 */

namespace Repository;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param User $model
       */

    public function model()
    {
        return User::class;
    }

    public function getUserByRole(int $role_id, string $user_code = null) {
        $query = $this->model->whereHas(
            'roles', function ($query) use($role_id){
                $query->where('id', $role_id);
            }
        );
        if ($user_code !== null) {
            $query = $query->where('user_code', $user_code);
        }

        return $query->paginate(20);
    }


}
