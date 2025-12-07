<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
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
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.item' => 'required|string|max:255',
            'data.*.jenis_biaya' => 'required',
            'data.*.nilai' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'data.*.item.required' => 'Kolom Item wajib diisi.',
            'data.*.jenis_biaya.required' => 'Kolom Jenis Biaya wajib dipilih.',
            'data.*.nilai.required' => 'Kolom Nilai wajib diisi.',
        ];
    }
}
