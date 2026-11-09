<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:200'],
            'pen_name' => ['nullable', 'string', 'max:100'],
            'editor_name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:book_categories,id'],
            'target_reader' => ['required', 'in:children,teen,adult,general'],
            'synopsis' => ['required', 'string', 'min:50'],
            'manuscript_file' => ['required', 'file', 'mimes:docx,pdf', 'max:51200'],
            'layout_file' => ['nullable', 'file', 'max:102400'],
            'estimated_pages' => ['required', 'integer', 'min:40'],
            'quantity' => ['required', 'integer', 'min:10'],
            'cover_type' => ['required', 'in:soft,hard'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'editor_name.required' => 'Nama editor wajib diisi.',
            'category_id.required' => 'Kategori/genre buku wajib dipilih.',
            'target_reader.required' => 'Target pembaca wajib dipilih.',
            'synopsis.required' => 'Sinopsis buku wajib diisi.',
            'synopsis.min' => 'Sinopsis minimal 50 karakter agar dapat dipahami editor.',
            'manuscript_file.required' => 'Berkas naskah (.docx / .pdf) wajib diunggah.',
            'manuscript_file.mimes' => 'Format naskah harus berupa .docx atau .pdf.',
            'manuscript_file.max' => 'Ukuran naskah maksimal 50 MB.',
            'estimated_pages.required' => 'Estimasi jumlah halaman wajib diisi.',
            'estimated_pages.min' => 'Minimal halaman buku adalah 40 halaman A5.',
            'quantity.required' => 'Oplah cetak wajib diisi.',
            'quantity.min' => 'Minimal oplah cetak adalah 10 eksemplar.',
            'cover_type.required' => 'Jenis sampul buku wajib dipilih.',
        ];
    }
}
