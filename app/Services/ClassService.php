<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;
use App\Services\Contracts\BaseServiceInterface;
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
}
