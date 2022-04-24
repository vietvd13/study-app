<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;

use App\Jobs\TestImport;
use App\Models\Classes;
use App\Models\Test;
use App\Services\Contracts\BaseServiceInterface;
use Service\BaseService;
use App\Services\Contracts\TestServiceInterface;
use App\Repositories\Contracts\TestRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class TestService extends BaseService implements TestServiceInterface
{

    protected $repository;

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function importTest($request) {
        $test_id = (isset($request->test_id)) ? $request->test_id : null;
        $class_id =  $request->class_id;
        $course_id =  $request->course_id;
        $class = Classes::find($class_id);
        $course = $class->courses()->find($course_id);
        $fileImport = $request->file('file');
        if ($class) {
            if ($course) {
                if ($fileImport) {
                    if ($test_id == null) {
                        $test = Test::create([
                            'limit_time' => $request->limit_time,
                            'test_name' => $request->test_name,
                            'blind_support' => $request->blind_support,
                            'course_id' => $request->course_id,
                            'class_id' => $request->class_id,
                            'created_by' => $request->user()->id
                        ]);
                        $test_id = $test->id;
                    } else {
                        $test = Test::where('id', $test_id)->update([
                            'limit_time' => $request->limit_time,
                            'test_name' => $request->test_name,
                            'blind_support' => $request->blind_support,
                            'course_id' => $request->course_id,
                            'class_id' => $request->class_id,
                            'created_by' => $request->user()->id
                        ]);
                        Storage::deleteDirectory("/testfiles/{$test_id}");
                    }
                    $pathFile = $this->uploadFile($fileImport, $fileImport->getClientOriginalName(), "testfiles/{$test_id}");
                    $queued = TestImport::dispatch([
                        'test_id' => $test_id,
                        'file_path' => $pathFile,
                        'folder_path' => "testfiles/{$test_id}",
                        'blind' => ($request->blind_support == 0) ? false : true
                    ])->onQueue('test_import');
                    return [
                        'test' => $test,
                        'queued_file' => $pathFile
                    ];
                }
                return [];
            }
        }
        return [
            'code' => 500,
            'message' => 'faild'
        ];
    }
}
