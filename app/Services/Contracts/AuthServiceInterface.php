<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-03
 */

namespace App\Services\Contracts;
use App\Models\User;

interface AuthServiceInterface extends BaseServiceInterface
{
    public function login(string $email, string $password): ?array;
    public function authUser(User $user): ?array;
}
