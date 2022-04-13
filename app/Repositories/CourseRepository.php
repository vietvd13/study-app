<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace Repository;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{

     public function __construct(Application $app)
     {
         parent::__construct($app);

     }

    /**
       * Instantiate model
       *
       * @param Course $model
       */

    public function model()
    {
        return Course::class;
    }

    public function addTeacher(array $data, int $course_id) {
        $status = $this->transaction(function () use ($data, $course_id) {
            $course = $this->model->where([
                'id' => $course_id
            ])->first();

            if ($data) {
                foreach ($data as $key => &$value) {
                    $value['course_id'] = $course_id;
                }
            }
            $course->teachers()->sync([]);
            $course = $course->teachers()->sync($data);
            return $course;
        });
        return $status;
    }

    public function addDocument($attributes) {
        // if (isset($attributes['files'])) {
        //     foreach ($attributes['files'] as $key => $file) {
        //         $pathFile = $this->uploadFile($file, "attatch{$attributes['course_id']}");
        //         $fileAttached[] = [
        //             'course_id' => $attributes['course_id'],
        //             'teacher_id'  => $attributes['teacher_id'],
        //             'name'  => $attributes['name'],
        //             'description'  => $attributes['description'],
        //             'path'  => $pathFile,
        //         ];
        //     }
        // }
        // return $fileAttached;
    }
}
