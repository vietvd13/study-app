<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-29
 */

namespace Repository;

use App\Models\Speech;
use App\Repositories\Contracts\SpeechRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;

class SpeechRepository extends BaseRepository implements SpeechRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);
     }

    /**
       * Instantiate model
       *
       * @param Speech $model
       */

    public function model()
    {
        return Speech::class; 
    }
}
