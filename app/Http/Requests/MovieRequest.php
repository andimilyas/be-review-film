<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
            'title'=>'required|max:255',
            'summary'=>'required',
            'year'=>'required|string',
            'poster'=>'mimes:jpg,bmp,png',
            'genre_id'=>'required|exists:genres,id'
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul tidak boleh melebihi dari 255 karakter',
            'summary.required' => 'Summary harus diisi',
            'year.required' => 'Tahun harus diisi',
            'genre_id.required' => 'Id harus diisi',
            'genre_id.exists' => 'Genre id tidak ditemukan'
        ];
    }
}
