<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;
use App\Services\Contracts\BaseServiceInterface;
use Service\BaseService;
use App\Services\Contracts\CourseServiceInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
class CourseService extends BaseService implements CourseServiceInterface
{

    protected $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addTeacher($request)
    {
        return $this->repository->addTeacher($request->data, $request->course_id);
    }

    public function addDocument($attributes, $teacher_id) {
        $fileAttached = [];
        if (isset($attributes['files'])) {
            $course = $this->repository->find($attributes['course_id']);
            if ($course) {
                foreach ($attributes['files'] as $key => $file) {
                    $pathFile = $this->uploadFile($file, $file->getClientOriginalName(), "documents/{$attributes['course_id']}");
                    $fileAttached[] = [
                        'course_id' => $attributes['course_id'],
                        'teacher_id'  => $teacher_id,
                        'name'  => $attributes['name'],
                        'description'  => $attributes['description'],
                        'path'  => $pathFile,
                    ];
                }
                $course->files()->createMany($fileAttached);
            }
        }
        return $fileAttached;
    }

    public function deleteDocuments($course_id, $document_id) {
        $course = $this->repository->find($course_id);
        if ($course) {
            $course->files()->where('id', $document_id)->delete();
            return $course;
        }
    }

    public function downloadDocument($course_id, $document_id) {
        $course = $this->repository->find($course_id);
        if ($course) {
            $file = $course->files()->where('id', $document_id)->first();
            // $path = $file->path;
            // if ()
            return $course;
        }
    }
}
