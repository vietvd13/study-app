<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ClassResource;
use App\Services\Contracts\ClassServiceInterface;
use Illuminate\Http\Request;
class ClassController extends Controller
{

     /**
     * var service
     */
    protected $service;

    public function __construct(ClassServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(ClassRequest $request)
    {
        $data = $this->service->paginate($request->per_page);
        return $this->responseJson(200, $data);
    }

    public function store(ClassRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return $this->responseJson(200, new ClassResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $data = $this->service->find($id);
            return $this->responseJson(200, new ClassResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(ClassRequest $request, $id)
    {
        $attributes = $request->except([]);
        $data = $this->service->update($attributes, $id);
        return $this->responseJson(200, new ClassResource($data));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }

    public function students(ClassRequest $request) {

    }

    public function AddStudent(ClassRequest $request) {
        return $this->service->addStudent($request);
    }

    public function AddCourses(ClassRequest $request) {
        return $this->service->addCourse($request);
    }

    public function ListActionInClass(Request $request) {
        return $this->service->ListActionInClass($request);
    }

    public function ClassAction(Request $request) {
        return $this->service->addClassAction($request);
    }

    public function ActionHandin(Request $request) {
        return $this->service->ActionHandin($request);
    }

    public function ActionGrading(Request $request) {
        return $this->service->ActionGrading($request);
    }

    public function GetAllHandinTeacher(Request $request) {
        return $this->service->allHandin($request);
    }
}
