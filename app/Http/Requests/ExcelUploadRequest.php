<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah ke `true` agar bisa digunakan
    }

    public function rules(): array
    {
        return [
            'excel1' => 'required|file|mimes:xlsx,xls|max:2048', // Maks 2MB
            'excel2' => 'nullable|file|mimes:xlsx,xls|max:2048', // File opsional
        ];
    }

    public function messages(): array
    {
        return [
            'excel1.required' => 'File Excel wajib diunggah.',
            'excel1.file' => 'File yang diunggah harus berupa file.',
            'excel1.mimes' => 'Format file harus .xlsx atau .xls.',
            'excel1.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ];
    }
}
