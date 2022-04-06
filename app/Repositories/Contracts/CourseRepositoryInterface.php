<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Repositories\Contracts;


interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function addTeacher(array $data, int $course_id);
}
