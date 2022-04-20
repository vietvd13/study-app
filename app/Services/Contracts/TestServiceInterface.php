<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Services\Contracts;


interface TestServiceInterface extends BaseServiceInterface
{
    public function importTest($request);
}
