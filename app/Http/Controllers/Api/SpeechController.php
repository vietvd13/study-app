<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-29
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\SpeechServiceInterface;
class SpeechController extends Controller
{

   protected $service;

    public function __construct(SpeechServiceInterface $service)
    {
       $this->service = $service;
    }

    public function userIntent(Request $request) {
        return $this->service->userVoiceController($request);
    }
}
