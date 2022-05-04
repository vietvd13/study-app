<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Repositories\Contracts;


interface ClassRepositoryInterface extends BaseRepositoryInterface
{
    public function addStudent(array $data, int $class_id);
    public function addCourse(array $data, int $class_id);
    public function addClassAction(int $class_id, int $teacher_id, string $name, string $description);
    public function ActionHandin(int $action_id, int $class_id, int $student_id, string $description, $files = null);
    public function ActionGrading(int $student_handin_id, int $grade, string $comment);
    public function allHandin(int $action_id, int $per_page);
    public function getClassByTeacher(int $teacher_id);
    public function allActions(int $class_id, int $per_page=10);
    public function ListClassStudent(int $student_id, int $per_page = 10);
    public function actionDetail(int $action_id, int $student_id);
}
