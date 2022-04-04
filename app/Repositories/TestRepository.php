<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace Repository;

use App\Models\Test;
use App\Repositories\Contracts\TestRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class TestRepository extends BaseRepository implements TestRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param Test $model
       */

    public function model()
    {
        return Test::class;
    }


}
