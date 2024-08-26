<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password'=>'required|string|min:8',
            'password_confirmation'=>'required|string|min:8|same:password',
        ];
    }

    public function messages(): array 
    {
        return [
            'name.required' => 'Name harus diisi',
            'name.max' => 'Name tidak boleh melebihi dari 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus valid',
            'email.max' => 'Email tidak boleh melebihi dari 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.required' => 'Password Confirmation harus diisi',
            'password_confirmation.min' => 'Password Confirmation minimal 8 karakter',
            'password_confirmation.same' => 'Password Confirmation harus sama dengan password',
        ];  
    }
}
