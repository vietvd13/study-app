<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Services\Contracts\AuthServiceInterface;

class AuthController extends Controller
{

     /**
     * var Repository
     */
    protected $service;

    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    public function login(AuthRequest $request)
    {
        try {
            $login = $this->service->login($request->email, $request->password);
            if ($login['status'] == 200) {
                return $this->responseJson($login['status'], new AuthResource($login['data']));
            } else {
                return $this->responseJsonError($login['status'], $login['message']);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function user(AuthRequest $request)
    {
        try {
            $auth = $this->service->authUser($request->user());
            if ($auth['status'] == 200) {
                return $this->responseJson($auth['status'], new AuthResource($auth['data']));
            } else {
                return $this->responseJsonError($auth['status'], $auth['message']);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
