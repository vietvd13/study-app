<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\TestServiceInterface;
class TestController extends Controller
{

     /**
     * var service
     */
    protected $service;

    public function __construct(TestServiceInterface $service)
    {
        $this->service = $service;
    }

    public function importTest(Request $request) {
        $file = $request->file('file');
        return $file;
    }
}
