<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-01
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use App\Models\User;
class UserRequest extends FormRequest
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
            return  [
                User::PHONE => 'required|string',
                User::NAME => 'required|string',
                User::EMAIL => 'in:inactive',
                User::PASSWORD => 'in:inactive',
                User::NEW_PASSWORD => 'regex:/^\S*$/u|min:8',
                User::ROLE => 'required|integer',
                User::STATUS => 'required|integer',
                User::BLIND => 'required|integer',
            ];
        }
        if(Route::getCurrentRoute()->getActionMethod() == 'store'){
            return  [
                User::PHONE => 'required|string',
                User::NAME => 'required|string',
                User::EMAIL => 'required|email',
                User::PASSWORD => 'required|regex:/^\S*$/u|min:8',
                User::NEW_PASSWORD => 'in:inactive',
                User::ROLE => 'required|integer',
                User::STATUS => 'required|integer',
                User::BLIND => 'required|integer',
            ];
        }
     }

    public function messages()
    {
        return [
            'required' => ':attribute not null',
            User::PASSWORD . ".regex" => 'The :attribute must has no blank space',
            User::EMAIL . ".in" => "The :attribute can not be update",
            User::PASSWORD . ".in" => "The :attribute must be 'new_password'"
        ];
    }
}
