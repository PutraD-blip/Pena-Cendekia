<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fiksi & Sastra',
            'Novel & Roman',
            'Buku Anak & Dongeng',
            'Pendidikan & Pelajaran',
            'Agama & Spiritual',
            'Pengembangan Diri & Motivasi',
            'Biografi & Autobiografi',
            'Sains & Teknologi',
            'Komik & Ilustrasi',
            'Puisi & Antologi',
            'Karya Ilmiah & Jurnal',
        ];

        foreach ($categories as $cat) {
            DB::table('book_categories')->insert([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
