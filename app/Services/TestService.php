<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;
use App\Services\Contracts\BaseServiceInterface;
use Service\BaseService;
use App\Services\Contracts\TestServiceInterface;
use App\Repositories\Contracts\TestRepositoryInterface;
class TestService extends BaseService implements TestServiceInterface
{

    protected $repository;

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function importTest($request) {

    }
}
