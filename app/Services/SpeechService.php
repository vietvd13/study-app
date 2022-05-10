<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-02-26
 */

namespace Service;
use App\Services\Contracts\SpeechServiceInterface;
use Illuminate\Support\Facades\Http;
class SpeechService extends BaseService implements SpeechServiceInterface
{
    public function userVoiceController($request) {
        $text = $this->speechToText($request);
        return $this->textToIntent($text);
    }

    private function speechToText($request) {
        $file = $request->file('user_voice')->getRealPath();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => env('FPT_TTS_API_URL'),
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => file_get_contents($file),
            CURLOPT_HTTPHEADER => array(
                "api-key: " . env('FPT_TTS_API_KEY')
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        $userVoiceInText = $response->hypotheses[0]->utterance;
        return $userVoiceInText;
    }

    private function textToIntent($text) {
        $curl = env('FPT_URL') . "/v3/predict";
        $response = Http::withToken(env('FPT_AI_APP_KEY'))
        ->post(
            $curl,
            [
                'content' => $text,
                'save_history' => false
            ]
        );
        $res = json_decode($response->body());
        if ($res->data->intents[0]->confidence > 0.5){
            if ($res->data->intents[0]->label == "open_test" || $res->data->intents[0]->label == "view_grade") {
                return [
                    'status' => 200,
                    'action' => $res->data->intents[0]->label,
                    'data' => [
                        "type" => $res->data->entities[0]->entity,
                        "value" => $res->data->entities[0]->value
                    ]
                ];
            } else if (in_array($res->data->intents[0]->label, [
                "answer_01",
                "answer_02",
                "answer_03",
                "answer_04"
            ])) {
                return   [
                    'status' => 200,
                    'action' => "answer",
                    'data' => [
                        "type" => $res->data->entities[0]->entity,
                        "value" => $res->data->entities[0]->value
                    ]
                ];
            }
            return [
                'status' => 200,
                'action' => $res->data->intents[0]->label
            ];
        } else if ($res->data->intents[0]->confidence <= 0.5) {
            return [
                'status' => 500,
                'message' => 'undifined the voice request'
            ];
        }
    }
}
