<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
          switch (Route::getCurrentRoute()->getActionMethod()){
                case 'update':
                    return $this->getCustomRule();
                case 'store':
                    return $this->getCustomRule();
                default:
                    return [];
          }
    }

     public function getCustomRule(){
        if(Route::getCurrentRoute()->getActionMethod() == 'update'){
            return [
                Course::NAME => 'required|string',
                Course::LEVEL => 'in:inactive',
            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return [
                Course::NAME => 'required|string',
                Course::LEVEL => 'int',
            ];
        }
     }

    public function messages()
    {
        return [
            'required' => ':attribute not null'
        ];
    }
}
