<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            'email'=>'required|email|min:6',
            'password'=>'required|min:6'
        ];
    }
    
    public function messages()
    {
        return [
            'required'=>'Vui lòng không để trống trường này',
            'email'=>'Email là bắt buộc'
        ];
    }

}
