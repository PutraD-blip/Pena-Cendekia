<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $profile = $this->user()->profile;

        return [
            'nik' => ['required', 'digits:16', 'unique:author_profiles,nik,' . ($profile?->id ?? 'NULL')],
            'address' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    /**
     * Custom error messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar oleh pengguna lain.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat minimal 10 karakter.',
        ];
    }
}
