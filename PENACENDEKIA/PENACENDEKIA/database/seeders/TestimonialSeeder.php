<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Dr. Lilik Huriyah, M.Pd.I',
                'role' => 'Dosen UIN Sunan Ampel Surabaya',
                'quote' => 'Prosesnya sangat cepat, pelayanannya bagus, desain cover dan layout isi sangat bagus, harganya sangat terjangkau.',
                'photo' => 'images/lilik-huriyah-hd.jpg',
                'rating' => 5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Reiza Praselanova',
                'role' => 'Motivator & Penulis Nasional',
                'quote' => 'Berkah bagi kami menemukan penerbit Pena Cendekia. Kualitas cetak prima, pelayanan memuaskan dan menentramkan hati.',
                'photo' => 'images/reiza-hd.jpg',
                'rating' => 5,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Yuni Ernawati',
                'role' => 'Guru & Penulis Buku Edukatif',
                'quote' => 'Pena Cendekia memberikan pelayanan yang sangat baik dalam menerbitkan buku dan biaya penerbitan sangat terjangkau di saku.',
                'photo' => 'images/yuni-hd.jpg',
                'rating' => 5,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['name' => $t['name']],
                $t
            );
        }
    }
}
