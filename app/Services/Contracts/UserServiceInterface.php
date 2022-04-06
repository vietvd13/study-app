<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-03
 */

namespace App\Services\Contracts;


interface UserServiceInterface extends BaseServiceInterface
{
    public function students($request);

    public function teacher($request);
}
