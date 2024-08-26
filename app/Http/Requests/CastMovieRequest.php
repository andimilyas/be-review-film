<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastMovieRequest extends FormRequest
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
            'name'=>'required|max:255',
            'cast_id'=>'required|exists:casts,id',
            'movie_id'=>'required|exists:movies,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'cast_id.required' => 'Id harus diisi',
            'cast_id.exists' => 'Cast id tidak ditemukan',
            'movie_id.required' => 'Id harus diisi',
            'movie_id.exists' => 'Movie id tidak ditemukan'
        ];
    }
}
