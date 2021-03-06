<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Services\Contracts;


interface CourseServiceInterface extends BaseServiceInterface
{
    public function addTeacher($request);
    public function addDocument($request, $teacher_id);
    public function deleteDocuments($course_id, $document_id);
}
