<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'critic'=>'required',
            'rating'=>'required|integer',
            'movie_id'=>'required|exists:movies,id'
        ];
    }
    public function messages(): array
    {
        return [
            'critic.required' => 'Kritik harus diisi',
            'rating.required' => 'Rating harus diisi',
            'movie_id.required' => 'Id harus diisi',
            'movie_id.exists' => 'Movie id tidak ditemukan'
        ];
    }
}
