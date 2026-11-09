<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeaturedBook;

class FeaturedBookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Administrasi Rekam Medis',
                'subtitle' => 'Tata Kelola Rekam Medis Modern & Digital di Pelayanan Kesehatan',
                'category' => 'Buku Ajar / Kesehatan',
                'cover_image' => 'images/books/administrasi-rekam-medis.jpg',
                'author' => 'Dr. Aries Wahyuningsih, S.Kep., Ns., M.Kes, Indraningrum Fitria, Dr. H. Henry Sudiyanto, dkk.',
                'editor' => 'Mangindara, S.K.M., M.Kes',
                'isbn' => '978-623-8123-01-4',
                'year' => '2024',
                'pages' => 'xii + 214 hlm',
                'size' => '15.5 x 23 cm (UNESCO)',
                'synopsis' => 'Buku ajar komprehensif yang dirancang untuk akademisi, mahasiswa, dan praktisi manajemen informasi kesehatan. Membedah tata kelola rekam medis manual hingga Rekam Medis Elektronik (RME) modern, kerahasiaan data pasien, standar akreditasi rumah sakit, serta sistem klasifikasi kodefikasi klinis.',
                'testimonial_quote' => 'Pelayanan Penerbit Pena Cendekia sangat memuaskan, proses cepat, dan kualitas cetak buku ajar kami sangat rapi dan berstandar internasional.',
                'testimonial_author' => 'Dr. Aries Wahyuningsih, S.Kep., Ns., M.Kes',
                'testimonial_role' => 'Dosen & Praktisi Kesehatan',
                'testimonial_photo' => 'images/books/administrasi-rekam-medis.jpg',
                'rating' => 5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Fikih Kebinekaan Modern',
                'subtitle' => "Perspektif Syekh Zainuddin Al-Malibari Dalam Kitab Fath Al-Mu'in",
                'category' => 'Pemikiran Islam & Kebangsaan',
                'cover_image' => 'images/books/fikih-kebinekaan-modern.png',
                'author' => "Prof. Dr. H. Saiful Jazil, M.Ag, Dr. Muh. Solihuddin, M.H.I, Prof. Dr. H. Syamsun Ni'am, M.Ag, dkk.",
                'editor' => 'Ibnus Shofi, S.Pd., M.Ag',
                'isbn' => '978-623-8123-02-1',
                'year' => '2024',
                'pages' => 'xiv + 268 hlm',
                'size' => '15.5 x 23 cm',
                'synopsis' => "Merekontruksi dan mengontekstualisasikan ajaran fiqih klasik Kitab Fath Al-Mu'in ke dalam dinamika kebangsaan Indonesia yang pluralistik. Menjawab problematika toleransi, hubungan antarumat beragama, serta komitmen kebangsaan berbasis nilai moderasi Islam.",
                'testimonial_quote' => 'Berkah bagi kami menemukan penerbit Pena Cendekia. Kualitas cetak prima, pelayanan memuaskan dan menentramkan hati.',
                'testimonial_author' => 'Prof. Dr. H. Saiful Jazil, M.Ag',
                'testimonial_role' => 'Guru Besar & Cendekiawan Muslim',
                'testimonial_photo' => 'images/reiza-hd.jpg',
                'rating' => 5,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Dinamika Fikih Munakahat di Era Modern',
                'subtitle' => 'Kajian Hukum Perkawinan Islam, Transformasi Sosial & Regulasi Nasional',
                'category' => 'Hukum Islam & Keluarga',
                'cover_image' => 'images/books/dinamika-fikih-munakahat.jpg',
                'author' => 'Diyan Putri Ayu, S.H.I., M.Sy, Dr. Kalijunjung Hasibuan, M.Sy, Dr. Thoat Stiawan, S.H.I., M.H.I, dkk.',
                'editor' => 'Sumardi Efendi, S.H.I., M.Ag',
                'isbn' => '978-623-8123-03-8',
                'year' => '2024',
                'pages' => 'xvi + 312 hlm',
                'size' => '15.5 x 23 cm',
                'synopsis' => 'Mengupas tuntas dinamika hukum perkawinan Islam kontemporer, tantangan pernikahan beda yurisdiksi, dispensasi nikah, perlindungan hak perempuan dan anak pasca-perceraian, serta relevansi pembaharuan Kompilasi Hukum Islam (KHI).',
                'testimonial_quote' => 'Pena Cendekia memberikan pendampingan penulisan yang sangat detail dari proofreading hingga penerbitan ISBN resmi.',
                'testimonial_author' => 'Diyan Putri Ayu, S.H.I., M.Sy',
                'testimonial_role' => 'Dosen Hukum Islam',
                'testimonial_photo' => 'images/lilik-hd.jpg',
                'rating' => 5,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Digitalisasi Pendidikan Islam',
                'subtitle' => 'Konsep, Strategi, dan Implementasi Pembelajaran Abad 21',
                'category' => 'Pendidikan Islam & Teknologi',
                'cover_image' => 'images/books/digitalisasi-pendidikan-islam.jpg',
                'author' => 'Dr. Hj. Siti Yumnah, M.Pd.I, Muhammad Sulaiman, M.Pd.I, Dr. Bayu Ardiwansyah, M.Pd, dkk.',
                'editor' => 'Chairunnisa Djayadin, S.Pd., M.A',
                'isbn' => '978-623-8123-04-5',
                'year' => '2024',
                'pages' => 'xii + 230 hlm',
                'size' => '15.5 x 23 cm',
                'synopsis' => 'Panduan terapan integrasi teknologi digital dalam pengajaran pendidikan Islam. Membahas model blended learning, pemanfaatan AI dalam madrasah, pengembangan e-modul interaktif, hingga kesiapan literasi digital para pendidik.',
                'testimonial_quote' => 'Pena Cendekia memberikan pelayanan yang sangat baik dalam menerbitkan buku dan biaya penerbitan sangat terjangkau di saku.',
                'testimonial_author' => 'Dr. Hj. Siti Yumnah, M.Pd.I',
                'testimonial_role' => 'Dosen & Peneliti Pendidikan',
                'testimonial_photo' => 'images/yumnah-hd.jpg',
                'rating' => 5,
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($books as $book) {
            FeaturedBook::updateOrCreate(
                ['title' => $book['title']],
                $book
            );
        }
    }
}
