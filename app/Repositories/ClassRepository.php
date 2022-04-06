<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace Repository;

use App\Models\Classes;
use App\Repositories\Contracts\ClassRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class ClassRepository extends BaseRepository implements ClassRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param class $model
       */

    public function model()
    {
        return Classes::class;
    }


}
