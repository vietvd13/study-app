<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-29
 */

namespace App\Services\Contracts;


interface SpeechServiceInterface extends BaseServiceInterface
{
    public function userVoiceController($request);
}
