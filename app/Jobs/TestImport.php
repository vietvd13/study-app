<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Imports\ImportTestQuestion;
use Throwable;
use App\Models\Test;
class TestImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $test;
    public function __construct($test)
    {
        $this->test = $test;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->testImport();
    }

    private function testImport() {
        $import =  new ImportTestQuestion(
            $this->test['test_id'],
            $this->test['folder_path'],
            $this->test['blind']
        );
        $test = Test::where('id', $this->test['test_id'])->first()->questions()->delete();
        Excel::import($import, Storage::path($this->test['file_path']));
        Storage::delete($this->test['file_path']);
    }
}
