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
}
