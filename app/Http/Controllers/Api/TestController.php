<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\TestServiceInterface;
class TestController extends Controller
{

     /**
     * var service
     */
    protected $service;

    public function __construct(TestServiceInterface $service)
    {
        $this->service = $service;
    }

    public function importTest(Request $request) {
        $file = $request->file('file');
        return $this->service->importTest($request);
    }

    public function answerQuestion(Request $request) {

    }

    public function testDetailTestByTeacher(Request $request) {
        return $this->service->testDetailByTeachers($request);
    }

    public function testDetailTestByStudent(Request $request) {
        return $this->service->testDetailByStudent($request);
    }

    public function studentListTestByClass(Request $request) {
        return $this->service->studentListTestByClass($request);
    }

    public function listTestCreatedByTeacher(Request $request) {
        return $this->service->listTestCreatedByTeacher($request);
    }

    public function testAnswer(Request $request) {
        return $this->service->studentAnswerTest($request);
    }

    public function studentViewGradeOfTheTest(Request $request) {
        return $this->service->studentViewGradeOfTheTest($request);
    }

    public function teacherViewGradeOfATest(Request $request) {
        return $this->service->teacherViewGradeOfATest($request);
    }
}
