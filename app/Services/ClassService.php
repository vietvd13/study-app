<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;
use Service\BaseService;
use App\Services\Contracts\ClassServiceInterface;
use App\Repositories\Contracts\ClassRepositoryInterface;
class ClassService extends BaseService implements ClassServiceInterface
{

    protected $repository;

    public function __construct(ClassRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addStudent($request) {
        return $this->repository->addStudent($request->data, $request->class_id);
    }

    public function addCourse($request) {
        return $this->repository->addCourse($request->data, $request->class_id);
    }
}
