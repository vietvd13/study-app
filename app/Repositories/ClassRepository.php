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
use Illuminate\Support\Carbon;

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

    public function addStudent(array $data, int $class_id) {
        $status = $this->transaction(function () use ($data, $class_id) {
            $class = $this->model->where([
                'id' => $class_id
            ])->first();

            if ($data) {
                foreach ($data as $key => &$value) {
                    $value['class_id'] = $class_id;
                }
            }
            $class->students()->sync([]);
            $class = $class->students()->sync($data);
            return $class;
        });
        return $status;
    }

    public function addCourse(array $data, int $class_id) {
        $status = $this->transaction(function () use ($data, $class_id) {
            $class = $this->model->where([
                'id' => $class_id
            ])->first();

            if ($data) {
                foreach ($data as $key => &$value) {
                    $value['class_id'] = $class_id;
                    $value['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $value['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            }
            $class->courses()->sync([]);
            $class = $class->courses()->sync($data);
            return $class;
        });
        return $status;
    }

    public function find($id, $columns = ['*'])
    {
        $class = $this->model->with([
            'students' => function ($query) {
                $query->select(['*']);
            },
            'courses' => function ($query) {
                $query->select(['*']);
            }
        ])->where([
            'id' => $id
        ])->first();
        return $class;
    }
}
