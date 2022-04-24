<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;

class ImportTestQuestion implements ToModel, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    use RemembersRowNumber;
    protected $test_id;
    public function __construct($test_id, $path_file, $is_blind =  false)
    {
        $this->test_id = $test_id;
        $this->blind = $is_blind;
    }

    public function model(array $row)
    {
        $answers = [];
        $rowIndex = $this->getRowNumber();
        $questionText = "";
        for ($i = 1; $i <= 4; $i++) {
            $answers[] = [
                'question_id' => null,
                'answer' => $row[$i],
                'is_correct' => ($row[5] == $i) ? true : false
            ];
        }
        if (count($answers) > 0) {
            $question = Question::create([
                'test_id' => $this->test_id,
                'question' => $row[0],
                'question_audio' => null
            ]);
            $questionText .= $question->question;
            for ($i = 0; $i < 4; $i++) {
                $answers[$i]['question_id'] = $question->id;
                $questionText .= " .Đáp án số " . ($i + 1)  . " là:" . $answers[$i]['answer'];
            }
            if ($this->blind) {
                $url = $this->fileAudioGenerate($questionText);
                sleep(5);
                $contents = file_get_contents($url);
                Storage::disk('local')->put("testfiles/{$this->test_id}/$question->id/question_{$question->id}.mp3", $contents);
                $question->question_audio = "testfiles/{$this->test_id}/$question->id/question_{$question->id}.mp3";
                $question->save();
            }
            $question->answers()->delete();
            $question->answers()->createMany($answers);
        }
    }

    public function audioStorage($file, $fileName, $path = "documents")
    {
        if ($file) {
            return $file->storeAs($path,
                "{$fileName}"
            );
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function fileAudioGenerate($text) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => 'https://api.fpt.ai/hmi/tts/v5',
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $text,
        CURLOPT_HTTPHEADER => array(
            'api-key: 19LFh1Vudd9MKK7LsSbZdacGP3mS5XoI',
            'speed: 0.5',
            'voice: banmai'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        $fileAudio = $response->async;
        return $fileAudio;
    }
}
