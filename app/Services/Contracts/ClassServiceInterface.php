<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Services\Contracts;


interface ClassServiceInterface extends BaseServiceInterface
{
    public function addStudent($request);
    public function addCourse($request);
    public function addClassAction($request);
    public function ActionHandin($request);
    public function ActionGrading($request);
    public function allHandin($request);
    public function getClassByTeacher($request);
}
