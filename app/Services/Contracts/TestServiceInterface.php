<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Services\Contracts;


interface TestServiceInterface extends BaseServiceInterface
{
    public function importTest($request);
    public function testDetailByTeachers($request);
    public function testDetailByStudent($request);
    public function studentListTestByClass($request);
    public function listTestCreatedByTeacher($request);
    public function studentAnswerTest($request);
    public function studentViewGradeOfTheTest($request);
    public function teacherViewGradeOfATest($request);
}
