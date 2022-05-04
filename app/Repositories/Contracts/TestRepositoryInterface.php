<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Repositories\Contracts;


interface TestRepositoryInterface extends BaseRepositoryInterface
{
    public function testDetailByTeachers(int $test_id);
    public function testDetailByStudent(int $test_id, int $student_id);
    public function listTestByClass(int $class_id, int $per_page);
    public function listTestCreatedByTeacher(int $teacher_id, int $per_page);
}
