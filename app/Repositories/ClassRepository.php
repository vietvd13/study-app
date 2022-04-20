<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace Repository;

use App\Models\ActionHandin;
use App\Models\ClassAction;
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


    public function addClassAction(int $class_id, int $teacher_id, string $name, string $description) {
        $class = $this->model->where('id', $class_id)->first();
        if ($class) {
            $classAction = $class->class_action()->create([
                'name' => $name,
                'description' => $description,
                'teacher_id' => $teacher_id,
                'class_id' => $class_id
            ]);
            return $classAction;
        }
        return [
            'stauts' => 500
        ];
    }

    public function ActionHandin(int $action_id, int $class_id, int $student_id, string $description, $filePath = null) {
        $class = $this->model->where('id', $class_id)->first();
        if ($class) {
            $action = $class->class_action()->where('id', $action_id)->first();
            if ($action) {
                $handin = $action->action_handin()->create([
                    'student_id' => $student_id,
                    'description' => $description,
                    'file_path' => $filePath
                ]);
            }
        }
        // ActionHandin::STUDENT_ID,
        // ActionHandin::ACTION_ID,
        // ActionHandin::NAME,
        // ActionHandin::DESCRIPTION,
        // ActionHandin::FILE_PATH
    }

    public function ActionGrading(int $student_handin_id, int $grade, string $comment) {
        $handin = ActionHandin::where('id', $student_handin_id)->first();
        if ($handin) {
            $handin->grade = $grade;
            $handin->comment = $comment;
            $handin->save();
            return [
                'status' => 200,
                'message' => 'ok'
            ];
        }
    }

    public function allHandin($action_id, $per_page = 10) {
        $class_actions = ClassAction::where('id', $action_id)->first()->action_handin()->paginate($per_page);
        return $class_actions;
    }

}
