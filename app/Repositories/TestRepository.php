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
use App\Models\StudentAnswer;
use App\Models\Answer;

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

    public function testDetailByTeachers(int $test_id) {

    }

    public function testDetailByStudent(int $test_id, int $student_id) {
        $test = $this->model->where('id', $test_id)->with([
            'questions' => function ($query) {
                $query->with([
                    'answers' => function ($query) {
                        $query->select(['id', 'answer', 'question_id']);
                    }
                ])->select(['*']);
            }
        ])->first();
        if ($test) {
            return [
                'test' => $test,
                'result' => $this->testResult($test, $student_id)
            ];
        } else {
            return [
                'code' => 200,
                'message' => 'test not found'
            ];
        }
    }

    public function listTestByClass(int $class_id, int $per_page) {
        return $this->model->where('class_id', $class_id)->paginate($per_page);
    }

    public function listTestCreatedByTeacher(int $teacher_id, int $per_page) {
        return $this->model->where('created_by', $teacher_id)->paginate($per_page);
    }

    private function testResult($testContents, $student_id) {
        $questionsIds = [];
        foreach ($testContents->questions as $question) {
            $questionsIds[] = $question->id;
        }
        $studentAnswer = StudentAnswer::whereIn('question_id', $questionsIds)
        ->where('student_id', $student_id)
        ->orderBy('created_at', 'DESC')
        ->get(['*']);

        $answersIds = [];
        foreach ($studentAnswer as $key => $answer) {
            $answersIds[] = $answer->answer_id;
        }

        $answers = Answer::whereIn('id', $answersIds)->where('is_correct', 1)->get(['*']);
        return [
            'corrected' => $answers,
            'correct_per_total' => count($answers) . " / " . count($questionsIds)
        ];
    }

}
