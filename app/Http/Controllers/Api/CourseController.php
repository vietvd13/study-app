<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use App\Services\Contracts\CourseServiceInterface;

class CourseController extends Controller
{
    protected $service;

    public function __construct(CourseServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(CourseRequest $request)
    {
        $data = $this->service->paginate($request->per_page);
        return $this->responseJson(200, $data);
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return $this->responseJson(200, new CourseResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $data = $this->service->find($id);
            return $this->responseJson(200, new CourseResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(CourseRequest $request, $id)
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

    public function AddTeacher(CourseRequest $request) {
        return $this->service->addTeacher($request);
    }

    public function courseDocuments(Request $request) {
        return $this->service->addDocument($request->all(), $request->user()->id);
    }

    public function deleteDocument(Request $request) {
        return $this->service->deleteDocuments($request->course_id, $request->document_id);
    }
}
