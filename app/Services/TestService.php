<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;

use App\Jobs\TestImport;
use App\Models\Classes;
use App\Models\Test;
use App\Services\Contracts\BaseServiceInterface;
use Service\BaseService;
use App\Services\Contracts\TestServiceInterface;
use App\Repositories\Contracts\TestRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentAnswer;
use Illuminate\Support\Carbon;
use App\Models\Question;
use App\Models\User;
class TestService extends BaseService implements TestServiceInterface
{

    protected $repository;

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function importTest($request) {
        $test_id = (isset($request->test_id)) ? $request->test_id : null;
        $class_id =  $request->class_id;
        $course_id =  $request->course_id;
        $class = Classes::find($class_id);
        $course = $class->courses()->find($course_id);
        $fileImport = $request->file('file');
        if ($class) {
            if ($course) {
                if ($fileImport) {
                    if ($test_id == null) {
                        $test = Test::create([
                            'limit_time' => $request->limit_time,
                            'test_name' => $request->test_name,
                            'blind_support' => $request->blind_support,
                            'course_id' => $request->course_id,
                            'class_id' => $request->class_id,
                            'created_by' => $request->user()->id
                        ]);
                        $test_id = $test->id;
                    } else {
                        $test = Test::where('id', $test_id)->update([
                            'limit_time' => $request->limit_time,
                            'test_name' => $request->test_name,
                            'blind_support' => $request->blind_support,
                            'course_id' => $request->course_id,
                            'class_id' => $request->class_id,
                            'created_by' => $request->user()->id
                        ]);
                        Storage::deleteDirectory("/testfiles/{$test_id}");
                    }
                    $pathFile = $this->uploadFile($fileImport, $fileImport->getClientOriginalName(), "testfiles/{$test_id}");
                    $queued = TestImport::dispatch([
                        'test_id' => $test_id,
                        'file_path' => $pathFile,
                        'folder_path' => "testfiles/{$test_id}",
                        'blind' => ($request->blind_support == 0) ? false : true
                    ])->onQueue('test_import');
                    return [
                        'test' => $test,
                        'queued_file' => $pathFile
                    ];
                }
                return [];
            }
        }
        return [
            'code' => 500,
            'message' => 'faild - course is not in the class or class not found'
        ];
    }

    public function testDetailByTeachers($request) {

    }

    public function testDetailByStudent($request) {
        return $this->repository->testDetailByStudent($request->test_id, $request->user()->id);
    }

    public function studentListTestByClass($request) {
        return $this->repository->listTestByClass($request->class_id, $request->per_page);
    }

    public function listTestCreatedByTeacher($request) {
        return $this->repository->listTestCreatedByTeacher($request->user()->id, $request->per_page);
    }

    public function teacherViewGradeOfATest($request) {
        $test = Test::where('id', $request->test_id)->first();
        if ($test) {
            $testQuestion = $test->questions()->with('answers')->get(['*']);
            $questionIds = [];
            foreach ($testQuestion as $key => $ques) {
                $questionIds[] = $ques->id;
                foreach ($ques['answers'] as $key => &$ans) {
                    $ans->selected = false;
                }
            }
            $testQuestion = $testQuestion->toArray();
            $whoAnswerTheTest = StudentAnswer::whereIn('question_id', $questionIds)->get(['*'])->groupBy('student_id');
            $studentsIDs = [];
            foreach ($whoAnswerTheTest as $key => $answer) {
                $studentsIDs[] = $answer[0]->student_id;
            }
            $students = User::whereIn('id', $studentsIDs)->get(['*']);
            foreach ($students as &$student) {
                $student->test_of_student = $testQuestion;
                foreach ($student->test_of_student as $key => $question) {
                    foreach ($question['answers'] as $key => &$ans) {
                        if (StudentAnswer::where('student_id', $student->id)->where('answer_id',  $ans['id'])->first()) {
                            $ans['selected'] = true;
                        }
                    }
                }
            }
            return $students;
        }

        return [
            'code' => 200,
            'message' => 'test not found'
        ];
    }

    public function studentAnswerTest($request) {
        $questions_ids = [];
        $answers = $request->answers;
        foreach ($answers as $key => $answer) {
            $questions_ids[] = $answer['question_id'];
            $answers[$key]['student_id'] = $request->user()->id;
            $answers[$key]['created_at'] = Carbon::now();
            $answers[$key]['updated_at'] = Carbon::now();
        }
        $checkAlreadyAnswer = StudentAnswer::where('student_id', $request->user()->id)
        ->whereIn('question_id', $questions_ids)->get(['*']);

        if (count($checkAlreadyAnswer) > 0) {
            return [
                'status' => 200,
                'message' => 'Student already answer this test'
            ];
        }
        if (StudentAnswer::insert($answers)) {
            return [
                'status' => 200,
                'message' => 'Submit done'
            ];
        }
    }

    public function studentViewGradeOfTheTest($request) {
        $student_id = $request->user()->id;
        $test_id = $request->test_id;
        $blind = $request->blind;
        $questions = Question::where('test_id', $test_id)->with([
            'answers' => function ($query) {
                $query->where('is_correct',  1)->select();
            }
        ])->get(['*']);
        $answerID = [];
        $questionsIds = [];
        foreach ($questions as $question) {
            $answerID[] = $question->answers[0]->id;
            $questionsIds[] = $question->id;
        }
        $correct = StudentAnswer::where('student_id', $student_id)
        ->whereIn('answer_id',  $answerID)->get(['*']);

        $totalOfAnswered = StudentAnswer::where('student_id', $student_id)
        ->whereIn('question_id',  $questionsIds)
        ->get(['*']);

        return [
            "total_of_questions" => count($questions),
            "total_of_answerd" => count($totalOfAnswered),
            "total_of_correct" => count($correct),
            "blind_support_file" => ($blind == true) ? $this->gradeToText($test_id, [
                "total_of_questions" => count($questions),
                "total_of_answerd" => count($totalOfAnswered),
                "total_of_correct" => count($correct)
            ]) : null
        ];
    }

    private function gradeToText($test_id, $grade) {
        $test = Test::find($test_id);
        if ($test) {
            $text = "Bài kiểm tra {$test->test_name}, bạn đã hoàn thành {$grade['total_of_answerd']} trên tổng số
            {$grade['total_of_questions']} câu hỏi của bài thi, bạn đã trả lời đúng {$grade['total_of_correct']} câu hỏi";
            return $this->GradeToAudio($text);
        }
        return $this->GradeToAudio("Rất tiếc, không có thông tin");
    }

    private function GradeToAudio($text) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => 'https://api.fpt.ai/hmi/tts/v5',
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $text,
        CURLOPT_HTTPHEADER => array(
            'api-key: 19LFh1Vudd9MKK7LsSbZdacGP3mS5XoI',
            'speed: 0.5',
            'voice: banmai'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        $fileAudio = $response->async;
        return $fileAudio;
    }
}
