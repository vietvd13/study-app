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
}
