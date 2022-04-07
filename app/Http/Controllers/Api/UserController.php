<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Services\Contracts\UserServiceInterface;
class UserController extends Controller
{

     /**
     * var Repository
     */
    protected $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }


    public function index(UserRequest $request)
    {
        $data = $this->service->paginate($request->per_page);
        return $this->responseJson(200, new UserResource($data));
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return $this->responseJson($data['status'], new UserResource($data['data']));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $user = $this->service->find($id);
            return $this->responseJson($user['status'], new UserResource($user['profile']));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(UserRequest $request, $id)
    {
        $attributes = $request->except([]);
        $data = $this->service->update($attributes, $id);
        return $this->responseJson(200, new BaseResource($data));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }

    public function roles() {
        return Role::all();
    }

    public function students(UserRequest $request) {
        return $this->service->students($request);
    }

    public function teacher(UserRequest $request) {
        return $this->service->teacher($request);
    }
}
