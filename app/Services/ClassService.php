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

    public function addClassAction($request) {
        return $this->repository->addClassAction(
            $request['class_id'],
            $request->user()->id,
            $request['name'],
            $request['description']
        );
    }

    public function ActionHandin($request) {
        $pathFile = null;
        if (isset($request->files) && count($request->files) > 0) {
            $file = $request['files'][0];
            $pathFile = $this->uploadFile($file, $file->getClientOriginalName(), "classactions");
        }
        return $this->repository->ActionHandin(
            $request['action_id'],
            $request['class_id'],
            $request->user()->id,
            $request['description'],
            $pathFile
        );
    }

    public function ActionGrading($request) {
        return $this->repository->ActionGrading(
            $request['student_handin_id'],
            $request['grade'],
            $request['comment']
        );
    }

    public function allHandin($request) {
        return $this->repository->allHandin(
            $request['action_id'],
            $request['per_page']
        );
    }

    public function listActionInClass($request) {
        return $this->repository->allActions(
            $request['class_id'],
            $request['per_page']
        );
    }

    public function getClassByTeacher($request) {
        return $this->repository->getClassByTeacher($request['teacher_id']);
    }
}
