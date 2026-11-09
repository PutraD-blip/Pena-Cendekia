<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $categories = BookCategory::where('is_active', true)->get();
        $testimonials = \App\Models\Testimonial::active()->ordered()->get();

        return view('public.home', compact('categories', 'testimonials'));
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        $accounts = PaymentAccount::where('is_active', true)->get();
        return view('public.contact', compact('accounts'));
    }

    public function templateBuku()
    {
        return view('public.template-buku');
    }

    public function templateHaki()
    {
        return view('public.template-haki');
    }

    public function layananPenerbitan()
    {
        return view('public.services.penerbitan-buku');
    }

    public function layananKonversi()
    {
        return view('public.services.konversi-ilmiah');
    }

    public function layananCetak()
    {
        return view('public.services.cetak-buku');
    }

    public function layananEditing()
    {
        return view('public.services.editing-naskah');
    }

    public function layananHki()
    {
        return view('public.services.pengurusan-hki');
    }

    public function printingOffset()
    {
        return view('public.printing-offset');
    }

    public function portofolio()
    {
        $books = $this->getPortfolioBooksList();
        $categories = BookCategory::where('is_active', true)->get();
        return view('public.portofolio', compact('books', 'categories'));
    }

    public function detailBuku($id)
    {
        $books = self::getPortfolioBooksList();
        
        $book = collect($books)->first(function ($item) use ($id) {
            $slug = \Illuminate\Support\Str::slug($item['title']);
            return $item['id'] === $id 
                || ($item['slug'] ?? '') === $id 
                || $slug === $id;
        });

        if (!$book) {
            return redirect()->route('portofolio')->with('error', 'Buku yang Anda cari tidak ditemukan dalam katalog.');
        }

        $relatedBooks = collect($books)->filter(function ($item) use ($book) {
            return $item['id'] !== $book['id'];
        })->take(3)->values()->all();

        return view('public.buku-detail', compact('book', 'relatedBooks'));
    }

    public static function getPortfolioBooksList(): array
    {
        try {
            $dbBooks = \App\Models\FeaturedBook::active()->ordered()->get();
            if ($dbBooks->isNotEmpty()) {
                return $dbBooks->map(function ($b) {
                    return [
                        'id' => 'buku-' . $b->id,
                        'db_id' => $b->id,
                        'slug' => \Illuminate\Support\Str::slug($b->title),
                        'title' => $b->title,
                        'sub' => $b->subtitle ?? '',
                        'category' => $b->category ?? 'Karya Terbitan',
                        'cover' => $b->cover_url,
                        'author' => $b->author,
                        'editor' => $b->editor ?? 'Tim Redaksi Pena Cendekia',
                        'isbn' => $b->isbn ?? '-',
                        'year' => $b->year ?? date('Y'),
                        'pages' => $b->pages ?? '200 hlm',
                        'size' => $b->size ?? '15.5 x 23 cm (UNESCO)',
                        'paper' => 'Bookpaper 72 gr (Kertas Buku Premium)',
                        'cover_type' => 'Soft Cover Doff Spot UV Emboss',
                        'availability' => 'Tersedia di Penerbit & Mitra',
                        'highlights' => [
                            'Terdaftar Resmi di Perpustakaan Nasional RI (Perpusnas)',
                            'Nomor ISBN & Barcode Terverifikasi',
                            'Standar Tata Letak UNESCO & Cetak Presisi',
                            'Penyuntingan & Koreksi Aksara Profesional'
                        ],
                        'synopsis' => $b->synopsis ?? '',
                        'testimonial_quote' => $b->testimonial_quote,
                        'testimonial_author' => $b->testimonial_author,
                        'testimonial_role' => $b->testimonial_role,
                        'testimonial_photo' => $b->testimonial_photo_url,
                        'rating' => $b->rating ?? 5,
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            // Fallback gracefully jika tabel belum di-migrate
        }

        return [
            [
                'id' => 'buku-1',
                'slug' => 'administrasi-rekam-medis',
                'title' => 'Administrasi Rekam Medis',
                'sub' => 'Tata Kelola Rekam Medis Modern & Digital di Pelayanan Kesehatan',
                'category' => 'Buku Ajar / Kesehatan',
                'cover' => 'images/books/administrasi-rekam-medis.jpg',
                'author' => 'Dr. Aries Wahyuningsih, S.Kep., Ns., M.Kes, Indraningrum Fitria, Dr. H. Henry Sudiyanto, dkk.',
                'editor' => 'Mangindara, S.K.M., M.Kes',
                'isbn' => '978-623-8123-01-4',
                'year' => '2024',
                'pages' => 'xii + 214 hlm',
                'size' => '15.5 x 23 cm (UNESCO)',
                'paper' => 'Bookpaper 72 gr (Kertas Buku Premium)',
                'cover_type' => 'Soft Cover Doff Spot UV Emboss',
                'availability' => 'Tersedia di Penerbit & Mitra',
                'highlights' => [
                    'Standar Akreditasi Rumah Sakit & Fasilitas Kesehatan Primer',
                    'Transisi Menuju Rekam Medis Elektronik (RME) Terintegrasi',
                    'Aspek Hukum, Etika, dan Kerahasiaan Rekam Medis Pasien',
                    'Sistem Klasifikasi, Kodifikasi Klinis, dan Pengarsipan Digital'
                ],
                'synopsis' => 'Buku ajar komprehensif yang dirancang untuk akademisi, mahasiswa, dan praktisi manajemen informasi kesehatan. Membedah tata kelola rekam medis manual hingga Rekam Medis Elektronik (RME) modern, kerahasiaan data pasien, standar akreditasi rumah sakit, serta sistem klasifikasi kodefikasi klinis.',
            ],
            [
                'id' => 'buku-2',
                'slug' => 'fikih-kebinekaan-modern',
                'title' => 'Fikih Kebinekaan Modern',
                'sub' => "Perspektif Syekh Zainuddin Al-Malibari Dalam Kitab Fath Al-Mu'in",
                'category' => 'Pemikiran Islam & Kebangsaan',
                'cover' => 'images/books/fikih-kebinekaan-modern.png',
                'author' => "Prof. Dr. H. Saiful Jazil, M.Ag, Dr. Muh. Solihuddin, M.H.I, Prof. Dr. H. Syamsun Ni'am, M.Ag, dkk.",
                'editor' => 'Ibnus Shofi, S.Pd., M.Ag',
                'isbn' => '978-623-8123-02-1',
                'year' => '2024',
                'pages' => 'xiv + 268 hlm',
                'size' => '15.5 x 23 cm (UNESCO)',
                'paper' => 'Bookpaper 72 gr (Kertas Buku Premium)',
                'cover_type' => 'Soft Cover Doff Spot UV Emboss',
                'availability' => 'Tersedia di Penerbit & Mitra',
                'highlights' => [
                    "Kajian Filologis & Tematis Kitab Klasik Fath Al-Mu'in",
                    'Kontekstualisasi Fikih Mazhab Syafii di Negara Kesatuan RI',
                    'Resolusi Kerukunan Antarumat Beragama Berbasis Maqashid Syariah',
                    'Inspirasi Moderasi Beragama untuk Generasi Intelektual Muslim'
                ],
                'synopsis' => "Merekontruksi dan mengontekstualisasikan ajaran fiqih klasik Kitab Fath Al-Mu'in ke dalam dinamika kebangsaan Indonesia yang pluralistik. Menjawab problematika toleransi, hubungan antarumat beragama, serta komitmen kebangsaan berbasis nilai moderasi Islam.",
            ],
            [
                'id' => 'buku-3',
                'slug' => 'dinamika-fikih-munakahat-di-era-modern',
                'title' => 'Dinamika Fikih Munakahat di Era Modern',
                'sub' => 'Kajian Hukum Perkawinan Islam, Transformasi Sosial & Regulasi Nasional',
                'category' => 'Hukum Islam & Keluarga',
                'cover' => 'images/books/dinamika-fikih-munakahat.jpg',
                'author' => 'Diyan Putri Ayu, S.H.I., M.Sy, Dr. Kalijunjung Hasibuan, M.Sy, Dr. Thoat Stiawan, S.H.I., M.H.I, dkk.',
                'editor' => 'Sumardi Efendi, S.H.I., M.Ag',
                'isbn' => '978-623-8123-03-8',
                'year' => '2024',
                'pages' => 'xvi + 312 hlm',
                'size' => '15.5 x 23 cm (UNESCO)',
                'paper' => 'Bookpaper 72 gr (Kertas Buku Premium)',
                'cover_type' => 'Soft Cover Doff Spot UV Emboss',
                'availability' => 'Tersedia di Penerbit & Mitra',
                'highlights' => [
                    'Dialektika Hukum Positif Perkawinan UU No. 16 Tahun 2019 dan Hukum Islam',
                    'Dinamika Batas Usia Nikah, Dispensasi Perkawinan, dan Isu Gender',
                    'Perlindungan Hak Perempuan & Anak Pasca Putusan Perceraian',
                    'Tinjauan Kontemporer Pernikahan Virtual, Siri, dan Kawin Campur'
                ],
                'synopsis' => 'Mengupas tuntas dinamika hukum perkawinan Islam kontemporer, tantangan pernikahan beda yurisdiksi, dispensasi nikah, perlindungan hak perempuan dan anak pasca-perceraian, serta relevansi pembaharuan Kompilasi Hukum Islam (KHI).',
            ],
            [
                'id' => 'buku-4',
                'slug' => 'digitalisasi-pendidikan-islam',
                'title' => 'Digitalisasi Pendidikan Islam',
                'sub' => 'Konsep, Strategi, dan Implementasi Pembelajaran Abad 21',
                'category' => 'Pendidikan Islam & Teknologi',
                'cover' => 'images/books/digitalisasi-pendidikan-islam.jpg',
                'author' => 'Dr. Hj. Siti Yumnah, M.Pd.I, Muhammad Sulaiman, M.Pd.I, Dr. Bayu Ardiwansyah, M.Pd, dkk.',
                'editor' => 'Chairunnisa Djayadin, S.Pd., M.A',
                'isbn' => '978-623-8123-04-5',
                'year' => '2024',
                'pages' => 'xii + 230 hlm',
                'size' => '15.5 x 23 cm (UNESCO)',
                'paper' => 'Bookpaper 72 gr (Kertas Buku Premium)',
                'cover_type' => 'Soft Cover Doff Spot UV Emboss',
                'availability' => 'Tersedia di Penerbit & Mitra',
                'highlights' => [
                    'Model Integrasi Teknologi AI & Pembelajaran Interaktif di Madrasah/Kampus',
                    'Strategi Pengembangan E-Modul dan Kurikulum Digital Pendidikan Agama Islam',
                    'Penguatan Literasi Digital Guru Tanpa Menanggalkan Nilai Karakter Islami',
                    'Studi Komparasi Kurikulum Merdeka Berbasis Ekosistem Digital'
                ],
                'synopsis' => 'Panduan terapan integrasi teknologi digital dalam pengajaran pendidikan Islam. Membahas model blended learning, pemanfaatan AI dalam madrasah, pengembangan e-modul interaktif, hingga kesiapan literasi digital para pendidik.',
            ],
        ];
    }
}
