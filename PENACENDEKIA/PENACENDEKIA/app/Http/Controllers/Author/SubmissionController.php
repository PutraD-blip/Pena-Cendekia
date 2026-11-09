<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\DesignTask;
use App\Models\BookCategory;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile()->first();
        if (!$profile || !$profile->isComplete()) {
            return redirect()->route('author.profile.edit')->with('warning', 'Harap lengkapi profil dan foto KTP Anda terlebih dahulu sebelum mengajukan naskah / layanan.');
        }

        // Definisi Katalog Seluruh Layanan Resmi Pena Cendekia
        $services = [
            'penerbitan' => [
                'id' => 'penerbitan',
                'title' => 'Paket Penerbitan Buku (Ber-ISBN)',
                'subtitle' => 'Paket penerbitan buku lengkap ber-ISBN resmi Perpustakaan Nasional RI, desain cover eksklusif, tata letak interior, dan cetak fisik.',
                'icon' => 'bi-book-half',
                'badge' => 'ISBN Resmi IKAPI',
                'templates' => [
                    ['name' => 'Template Naskah Buku A5 (.docx)', 'url' => asset('downloads/template-buku-a5.docx'), 'size' => 'A5 (14.8 x 21 cm)', 'desc' => 'Standar novel, antologi, puisi, dan buku umum.'],
                    ['name' => 'Template Naskah UNESCO (.docx)', 'url' => asset('downloads/template-buku-unesco.docx'), 'size' => 'UNESCO (15.5 x 23 cm)', 'desc' => 'Standar monograf, buku ajar, & referensi perguruan tinggi.'],
                    ['name' => 'Template Naskah Buku A4 (.docx)', 'url' => asset('downloads/template-buku-a4.docx'), 'size' => 'A4 (21 x 29.7 cm)', 'desc' => 'Standar modul praktikum, atlas, & lembar kerja siswa.'],
                    ['name' => 'Surat Pernyataan Keaslian Karya (.docx)', 'url' => asset('downloads/template-surat-keaslian-karya.docx'), 'size' => 'Surat Bermaterai (Wajib)', 'desc' => 'Wajib diisi, ditandatangani & diunggah saat pengajuan naskah.'],
                ],
                'packages' => [
                    'paket-3' => ['id' => 'paket-3', 'name' => 'Paket 3 Buku', 'price' => 750000, 'copies' => 3, 'badge' => 'Pemula', 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Menarik & Elegan', 'Tata Letak Standar UNESCO', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 3 Eksemplar', 'Bukti Serah Simpan Karya Cetak']],
                    'paket-5' => ['id' => 'paket-5', 'name' => 'Paket 5 Buku', 'price' => 800000, 'copies' => 5, 'badge' => 'Hemat', 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Menarik & Elegan', 'Tata Letak Standar UNESCO', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 5 Eksemplar', 'Bukti Serah Simpan Karya Cetak']],
                    'paket-10' => ['id' => 'paket-10', 'name' => 'Paket 10 Buku', 'price' => 1000000, 'copies' => 10, 'badge' => 'Paling Populer', 'highlight' => true, 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Kreatif & Eksklusif', 'Tata Letak & Proofing Profesional', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 10 Eksemplar', 'Bukti Serah Simpan Karya Cetak']],
                    'paket-25' => ['id' => 'paket-25', 'name' => 'Paket 25 Buku', 'price' => 1650000, 'copies' => 25, 'badge' => 'Rekomendasi', 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Profesional Eksklusif', 'Tata Letak & Proofing Buku', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 25 Eksemplar', 'Diskon Pengurusan HAKI Rp 50.000']],
                    'paket-50' => ['id' => 'paket-50', 'name' => 'Paket 50 Buku', 'price' => 2650000, 'copies' => 50, 'badge' => 'Komunitas', 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Premium Elegan', 'Tata Letak Standar UNESCO', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 50 Eksemplar', 'Diskon Pengurusan HAKI Rp 100.000']],
                    'paket-100' => ['id' => 'paket-100', 'name' => 'Paket 100 Buku', 'price' => 4500000, 'copies' => 100, 'badge' => 'Free HKI Resmi', 'highlight' => true, 'desc' => 'Maks 150 Halaman (A5 / UNESCO)', 'features' => ['Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Eksklusif & Proofing', 'Tata Letak Standar UNESCO', 'e-Sertifikat Penulis Resmi', 'Buku Cetak 100 Eksemplar', 'GRATIS Sertifikat HAKI Kemenkumham']],
                ]
            ],

            'hki' => [
                'id' => 'hki',
                'title' => 'Pengurusan HKI / Hak Cipta',
                'subtitle' => 'Pencatatan Surat Hak Cipta resmi DJKI Kemenkumham RI ber-barcode legal dalam 1 - 3 hari kerja.',
                'icon' => 'bi-shield-check',
                'badge' => 'DJKI Kemenkumham',
                'templates' => [
                    ['name' => 'Formulir Isian Permohonan Hak Cipta (.docx)', 'url' => asset('downloads/form-isian-hak-cipta.docx'), 'size' => 'Formulir Data Pencipta & Judul', 'desc' => 'Isikan identitas pencipta & data karya.'],
                    ['name' => 'Surat Pernyataan Hak Cipta Bermaterai (.docx)', 'url' => asset('downloads/surat-pernyataan-hak-cipta.docx'), 'size' => 'Surat Keaslian Ciptaan', 'desc' => 'Surat pernyataan bermaterai Rp 10.000.'],
                    ['name' => 'Surat Pengalihan Hak Cipta (.docx)', 'url' => asset('downloads/surat-pengalihan-hak-cipta.docx'), 'size' => 'Pengalihan Hak Cipta', 'desc' => 'Dokumen pengalihan hak kepada penerbit.'],
                ],
                'packages' => [
                    'hki-member' => ['id' => 'hki-member', 'name' => 'HKI Member Penulis', 'price' => 350000, 'copies' => 1, 'badge' => 'Khusus Penulis', 'highlight' => true, 'desc' => 'Karya yang diterbitkan di Pena Cendekia', 'features' => ['Sertifikat Resmi DJKI Kemenkumham RI', 'Proses Kilat 1 - 3 Hari Kerja Terbit', 'Surat Pencatatan Ciptaan Ber-barcode Resmi', 'Diakui untuk BKD/PAK Dosen & Angka Kredit Guru', 'Arsip Digital Selamanya di Server DJKI']],
                    'hki-reguler' => ['id' => 'hki-reguler', 'name' => 'HKI Reguler / Umum', 'price' => 400000, 'copies' => 1, 'badge' => 'Umum', 'desc' => 'Untuk naskah / karya mandiri umum', 'features' => ['Sertifikat Resmi DJKI Kemenkumham RI', 'Proses Kilat 1 - 3 Hari Kerja Terbit', 'Surat Pencatatan Ciptaan Ber-barcode Resmi', 'Pemeriksaan kelengkapan dokumen oleh tim HKI', 'Konsultasi klasifikasi jenis ciptaan']],
                    'hki-kolektif' => ['id' => 'hki-kolektif', 'name' => 'HKI Kolektif / Institusi', 'price' => 325000, 'copies' => 5, 'badge' => 'Hemat Kampus', 'desc' => 'Minimal 5 karya cipta sekaligus (Dosen/Sekolah)', 'features' => ['Diskon khusus pengurusan banyak karya cipta', 'Sertifikat Resmi DJKI Kemenkumham RI per karya', 'Bantuan pengisian berkas massal oleh staf HKI', 'Laporan rekapitulasi untuk LPPM / Institusi', 'Dukungan administrasi BKD/LKD']],
                ]
            ],
            'editing' => [
                'id' => 'editing',
                'title' => 'Editing & Tata Letak Naskah',
                'subtitle' => 'Penyempurnaan tata bahasa, EYD V, kalimat efektif, serta penataan tata letak interior buku standar penerbitan.',
                'icon' => 'bi-pencil-square',
                'badge' => 'Editor Profesional',
                'templates' => [
                    ['name' => 'Template Format Naskah A5 (.docx)', 'url' => asset('downloads/template-buku-a5.docx'), 'size' => 'A5 Standar Editing', 'desc' => 'Format standar pengiriman draf naskah.'],
                    ['name' => 'Template Format Naskah UNESCO (.docx)', 'url' => asset('downloads/template-buku-unesco.docx'), 'size' => 'UNESCO Standar Editing', 'desc' => 'Format standar buku perguruan tinggi.'],
                ],
                'packages' => [
                    'editing-ringan' => ['id' => 'editing-ringan', 'name' => 'Paket Editing Ringan', 'price' => 8000, 'copies' => 1, 'badge' => 'Proofreading', 'desc' => 'Rp 8.000 / Halaman Jadi', 'features' => ['Koreksi kesalahan ketik (typo)', 'Penyesuaian EYD V & PUEBI', 'Koreksi tanda baca & huruf kapital', 'Laporan catatan penyuntingan', 'Estimasi pengerjaan 3-5 hari kerja']],
                    'editing-sedang' => ['id' => 'editing-sedang', 'name' => 'Paket Editing Sedang', 'price' => 10000, 'copies' => 1, 'badge' => 'Paling Populer', 'highlight' => true, 'desc' => 'Rp 10.000 / Halaman Jadi', 'features' => ['Semua fitur Editing Ringan', 'Perbaikan struktur kalimat agar efektif', 'Penyelarasan diksi & variasi kosakata', 'Pengecekan koherensi antarparagraf', 'Estimasi pengerjaan 5-7 hari kerja']],
                    'editing-berat' => ['id' => 'editing-berat', 'name' => 'Paket Editing Berat (Substantif)', 'price' => 15000, 'copies' => 1, 'badge' => 'Menyeluruh', 'desc' => 'Rp 15.000 / Halaman Jadi', 'features' => ['Semua fitur Editing Sedang', 'Parafrase kalimat agar bebas plagiasi', 'Restrukturisasi alur bab yang membingungkan', 'Penyuntingan gaya bahasa menyeluruh', 'Konsultasi langsung dengan editor senior']],
                    'layout-saja' => ['id' => 'layout-saja', 'name' => 'Jasa Tata Letak / Layout Saja', 'price' => 5000, 'copies' => 1, 'badge' => 'Interior', 'desc' => 'Rp 5.000 / Halaman Jadi', 'features' => ['Tata letak interior buku profesional (Adobe InDesign)', 'Desain halaman judul, bab, dan footer rapi', 'Penataan tabel, gambar, & rumus matematis', 'Output PDF siap cetak + e-Book', 'Revisi layout hingga disetujui']],
                ]
            ],
            'konversi' => [
                'id' => 'konversi',
                'title' => 'Konversi KTI ke Buku',
                'subtitle' => 'Ubah Skripsi, Tesis, Disertasi, atau Laporan Penelitian menjadi buku referensi ber-ISBN resmi yang enak dibaca publik.',
                'icon' => 'bi-journal-bookmark-fill',
                'badge' => 'Konversi Akademik',
                'templates' => [
                    ['name' => 'Panduan Sistematika Konversi KTI ke Buku (.docx)', 'url' => asset('downloads/template-buku-unesco.docx'), 'size' => 'Sistematika Bab Populer', 'desc' => 'Panduan mengubah bab skripsi/tesis menjadi bab buku.'],
                    ['name' => 'Template Format Buku Referensi (.docx)', 'url' => asset('downloads/template-buku-unesco.docx'), 'size' => 'Format UNESCO Perguruan Tinggi', 'desc' => 'Format standar buku referensi Dikti.'],
                ],
                'packages' => [
                    'konversi-skripsi' => ['id' => 'konversi-skripsi', 'name' => 'Konversi Skripsi ke Buku', 'price' => 1500000, 'copies' => 10, 'badge' => 'Pemula', 'desc' => 'Ubah skripsi S1 menjadi buku referensi populer', 'features' => ['Penghilangan bahasa kaku format bab skripsi', 'Penyusunan bab mengalir & nyaman dibaca umum', 'Free ISBN & Barcode Resmi Perpusnas', 'Desain Kover Eksklusif & Layout Penuh', 'Cetak 10 Eksemplar Buku Fisik']],
                    'konversi-tesis' => ['id' => 'konversi-tesis', 'name' => 'Konversi Tesis ke Buku Referensi', 'price' => 2000000, 'copies' => 10, 'badge' => 'Dosen & Magister', 'highlight' => true, 'desc' => 'Standar buku referensi perguruan tinggi', 'features' => ['Transformasi tesis S2 menjadi buku ilmiah populer', 'Penyesuaian pedoman Dikti untuk angka kredit dosen', 'Free ISBN & Barcode Resmi Perpusnas', 'Tata Letak Standar UNESCO + Desain Kover', 'Cetak 10 Eksemplar Buku Fisik']],
                    'konversi-disertasi' => ['id' => 'konversi-disertasi', 'name' => 'Konversi Disertasi ke Monograf', 'price' => 2500000, 'copies' => 10, 'badge' => 'Doktor & Peneliti', 'desc' => 'Buku monograf ilmiah tingkat lanjut berbobot', 'features' => ['Rekonstruksi komprehensif disertasi S3', 'Fokus pada temuan orisinal & kebaruan riset', 'Free ISBN & Barcode Resmi Perpusnas', 'Tata Letak Standar UNESCO + Hard/Soft Cover', 'Cetak 10 Eksemplar Buku Fisik']],
                ]
            ]
        ];

        $serviceKey = $request->query('service', 'penerbitan');
        if (!array_key_exists($serviceKey, $services)) {
            $serviceKey = 'penerbitan';
        }

        $currentService = $services[$serviceKey];
        $packages = $currentService['packages'];

        $selectedPackageKey = $request->query('package');
        $selectedPackage = $packages[$selectedPackageKey] ?? reset($packages);

        $categories = BookCategory::where('is_active', true)->orderBy('name')->get();
        return view('author.submissions.create', compact('services', 'serviceKey', 'currentService', 'categories', 'packages', 'selectedPackage', 'selectedPackageKey', 'user', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile()->first();
        if (!$profile || !$profile->isComplete()) {
            return redirect()->route('author.profile.edit')->with('warning', 'Harap lengkapi profil dan foto KTP Anda terlebih dahulu.');
        }

        $validated = $request->validate([
            'service_type' => ['nullable', 'string', 'in:penerbitan,cetak,hki,editing,konversi'],
            'package_name' => ['required', 'string', 'max:150'],
            'package_price' => ['nullable', 'numeric'],
            'title' => ['required', 'string', 'max:200'],
            'pen_name' => ['nullable', 'string', 'max:100'],
            'editor_name' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:book_categories,id'],
            'target_reader' => ['nullable', 'in:children,teen,adult,general'],
            'synopsis' => ['required', 'string', 'min:15'],
            'manuscript_file' => ['required', 'file', 'mimes:docx,pdf,zip,rar,doc', 'max:51200'],
            'layout_file' => ['nullable', 'file', 'max:102400'],
            'originality_statement_file' => ['required', 'file', 'mimes:docx,doc,pdf,jpg,jpeg,png', 'max:20480'],
            'estimated_pages' => ['nullable', 'integer', 'min:1'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'cover_type' => ['nullable', 'in:soft,hard'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'package_name.required' => 'Pilihan paket / layanan wajib ditentukan.',
            'title.required' => 'Judul buku / karya wajib diisi.',
            'synopsis.required' => 'Sinopsis atau deskripsi pengajuan wajib diisi.',
            'synopsis.min' => 'Sinopsis / deskripsi minimal 15 karakter agar dapat dipahami redaksi.',
            'manuscript_file.required' => 'Berkas naskah / dokumen utama wajib diunggah.',
            'manuscript_file.mimes' => 'Format berkas naskah harus berupa .docx, .pdf, atau .zip.',
            'manuscript_file.max' => 'Ukuran berkas naskah maksimal 50 MB.',
            'originality_statement_file.required' => 'Surat Pernyataan Keaslian Karya wajib diunggah.',
            'originality_statement_file.mimes' => 'Format Surat Pernyataan Keaslian Karya harus berupa .docx, .doc, .pdf, atau gambar (.jpg, .png).',
            'originality_statement_file.max' => 'Ukuran berkas Surat Pernyataan Keaslian Karya maksimal 20 MB.',
        ]);

        $serviceType = $validated['service_type'] ?? 'penerbitan';

        // Default fallback values based on service type
        $defaultCategory = BookCategory::first()?->id ?? 1;
        $categoryId = $validated['category_id'] ?? $defaultCategory;
        $editorName = !empty($validated['editor_name']) ? $validated['editor_name'] : $user->name;
        $targetReader = $validated['target_reader'] ?? 'general';
        $coverType = $validated['cover_type'] ?? 'soft';
        $estimatedPages = $validated['estimated_pages'] ?? 100;
        $quantity = $validated['quantity'] ?? 1;

        // Upload Manuscript / Document
        $manuscriptFile = $request->file('manuscript_file');
        $manuscriptName = $serviceType . '_' . time() . '_' . Str::random(8) . '.' . $manuscriptFile->getClientOriginalExtension();
        $manuscriptFile->storeAs('private/manuscripts', $manuscriptName, 'local');

        // Upload Originality Statement (Wajib)
        $statementName = null;
        if ($request->hasFile('originality_statement_file')) {
            $statementFile = $request->file('originality_statement_file');
            $statementName = 'keaslian_' . time() . '_' . Str::random(8) . '.' . $statementFile->getClientOriginalExtension();
            $statementFile->storeAs('private/statements', $statementName, 'local');
        }

        // Upload Layout / Supporting file (optional)
        $layoutName = null;
        if ($request->hasFile('layout_file')) {
            $layoutFile = $request->file('layout_file');
            $layoutName = 'lampiran_' . time() . '_' . Str::random(8) . '.' . $layoutFile->getClientOriginalExtension();
            $layoutFile->storeAs('private/layouts_from_author', $layoutName, 'local');
        }

        $code = Submission::generateCode();

        $submission = Submission::create([
            'user_id' => $user->id,
            'code' => $code,
            'service_type' => $serviceType,
            'package_name' => $validated['package_name'],
            'package_price' => $validated['package_price'] ?? null,
            'title' => $validated['title'],
            'pen_name' => $request->input('pen_name'),
            'editor_name' => $editorName,
            'category_id' => $categoryId,
            'target_reader' => $targetReader,
            'synopsis' => $validated['synopsis'],
            'manuscript_file' => $manuscriptName,
            'layout_file' => $layoutName,
            'originality_statement_file' => $statementName,
            'estimated_pages' => $estimatedPages,
            'quantity' => $quantity,
            'cover_type' => $coverType,
            'notes' => $request->input('notes'),
            'status' => 'pending_review',
            'submitted_at' => now(),
        ]);

        ActivityLog::record('submission.created', 'Pengajuan baru: [' . $submission->service_type_label . '] ' . $submission->title, $submission, $user);

        NotificationService::notifyAdmins(
            'Pengajuan Baru: ' . $submission->service_type_label,
            "Penulis {$user->name} mengajukan {$submission->service_type_label} '{$submission->title}' ({$submission->code}) - Paket: {$submission->package_name}.",
            'info',
            'submission',
            $submission->id
        );

        NotificationService::send(
            $user->id,
            'Pengajuan Berhasil',
            "Pengajuan {$submission->service_type_label} '{$submission->title}' ({$submission->code}) telah diterima sistem dan langsung masuk ke antrean tinjauan Admin Redaksi.",
            'success',
            'submission',
            $submission->id
        );

        return redirect()->route('author.submissions.show', $submission)
            ->with('success', "Pengajuan {$submission->service_type_label} '{$submission->title}' berhasil diajukan dengan kode {$submission->code}! Admin Redaksi akan segera meninjau berkas Anda.");
    }

    public function show(Submission $submission)
    {
        $user = Auth::user();
        if ($submission->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $submission->load(['category', 'payments.account', 'designTasks.designer', 'revisions.admin']);
        return view('author.submissions.show', compact('submission'));
    }

    public function edit(Submission $submission)
    {
        $user = Auth::user();
        if ($submission->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($submission->status !== 'revision_required') {
            return redirect()->route('author.submissions.show', $submission)
                ->with('warning', 'Pengajuan ini tidak dalam status revisi.');
        }

        $categories = BookCategory::where('is_active', true)->orderBy('name')->get();
        return view('author.submissions.edit', compact('submission', 'categories'));
    }

    public function update(Request $request, Submission $submission)
    {
        $user = Auth::user();
        if ($submission->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($submission->status !== 'revision_required') {
            return redirect()->route('author.submissions.show', $submission);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'pen_name' => ['nullable', 'string', 'max:100'],
            'editor_name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:book_categories,id'],
            'target_reader' => ['required', 'in:children,teen,adult,general'],
            'synopsis' => ['required', 'string', 'min:50'],
            'manuscript_file' => ['nullable', 'file', 'mimes:docx,pdf', 'max:51200'],
            'layout_file' => ['nullable', 'file', 'max:102400'],
            'originality_statement_file' => ['nullable', 'file', 'mimes:docx,doc,pdf,jpg,jpeg,png', 'max:20480'],
            'estimated_pages' => ['required', 'integer', 'min:40'],
            'quantity' => ['required', 'integer', 'min:10'],
            'cover_type' => ['required', 'in:soft,hard'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $manuscriptName = $submission->manuscript_file;
        if ($request->hasFile('manuscript_file')) {
            $manuscriptFile = $request->file('manuscript_file');
            $manuscriptName = 'naskah_' . time() . '_' . Str::random(8) . '.' . $manuscriptFile->getClientOriginalExtension();
            $manuscriptFile->storeAs('private/manuscripts', $manuscriptName, 'local');
        }

        $layoutName = $submission->layout_file;
        if ($request->hasFile('layout_file')) {
            $layoutFile = $request->file('layout_file');
            $layoutName = 'layout_' . time() . '_' . Str::random(8) . '.' . $layoutFile->getClientOriginalExtension();
            $layoutFile->storeAs('private/layouts_from_author', $layoutName, 'local');
        }

        $statementName = $submission->originality_statement_file;
        if ($request->hasFile('originality_statement_file')) {
            $statementFile = $request->file('originality_statement_file');
            $statementName = 'keaslian_' . time() . '_' . Str::random(8) . '.' . $statementFile->getClientOriginalExtension();
            $statementFile->storeAs('private/statements', $statementName, 'local');
        }

        $submission->update([
            'title' => $validated['title'],
            'pen_name' => $request->input('pen_name'),
            'editor_name' => $validated['editor_name'],
            'category_id' => $validated['category_id'],
            'target_reader' => $validated['target_reader'],
            'synopsis' => $validated['synopsis'],
            'manuscript_file' => $manuscriptName,
            'layout_file' => $layoutName,
            'originality_statement_file' => $statementName,
            'estimated_pages' => $validated['estimated_pages'],
            'quantity' => $validated['quantity'],
            'cover_type' => $validated['cover_type'],
            'notes' => $request->input('notes'),
            'status' => 'pending_review',
        ]);

        ActivityLog::record('submission.revised', 'Penulis mengirimkan perbaikan naskah: ' . $submission->title, $submission, $user);

        NotificationService::notifyAdmins(
            'Revisi Naskah Dikirimkan',
            "Penulis {$user->name} telah mengirimkan perbaikan naskah '{$submission->title}' ({$submission->code}).",
            'info',
            'submission',
            $submission->id
        );

        return redirect()->route('author.submissions.show', $submission)
            ->with('success', 'Perbaikan naskah berhasil dikirimkan kembali ke Admin untuk ditinjau.');
    }

    public function approveDesign(Request $request, DesignTask $task)
    {
        $user = Auth::user();
        $submission = $task->submission;

        if ($submission->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$task->admin_approved || !$task->result_file) {
            return back()->with('error', 'Desain ini belum diverifikasi dan disetujui oleh Admin Redaksi.');
        }

        $task->update([
            'author_approved' => true,
            'author_approved_at' => now(),
            'author_notes' => $request->input('notes'),
        ]);

        $taskLabel = $task->task_type === 'layout' ? 'Layout Isi Buku' : 'Desain Cover';
        ActivityLog::record('design.author_approved', "Penulis menyetujui hasil {$taskLabel} untuk naskah '{$submission->title}'", $task, $user);

        // Periksa apakah semua tugas desain telah disetujui (Admin & Penulis)
        $submission->load('designTasks');
        if ($submission->isAllDesignApproved() && in_array($submission->status, ['in_design'])) {
            if ($submission->service_type === 'editing') {
                $submission->update(['status' => 'waiting_final_payment']);

                NotificationService::notifyAdmins(
                    "Seluruh Hasil Editing Telah Disetujui Penulis — Menunggu Pelunasan!",
                    "Penulis {$user->name} telah menyetujui seluruh hasil editing & tata letak naskah '{$submission->title}' ({$submission->code}). Naskah siap dilunasi oleh penulis.",
                    'success',
                    'submission',
                    $submission->id
                );

                return back()->with('success', "Alhamdulillah! Anda telah menyetujui hasil {$taskLabel}. Naskah Anda kini memasuki tahap Pelunasan (50%) untuk serah terima berkas final.");
            } else {
                $submission->update(['status' => 'ready_to_print']);

                NotificationService::notifyAdmins(
                    "Seluruh Desain Telah Disetujui Penulis — Siap Dicetak!",
                    "Penulis {$user->name} telah memeriksa dan MENYETUJUI seluruh hasil desain untuk buku '{$submission->title}' ({$submission->code}). Silakan klik 'Cetak Sekarang' untuk meneruskan ke tim percetakan.",
                    'success',
                    'submission',
                    $submission->id
                );

                return back()->with('success', "Alhamdulillah! Anda telah menyetujui hasil {$taskLabel}. Admin Redaksi akan segera meneruskan berkas ke tim percetakan.");
            }
        } else {
            NotificationService::notifyAdmins(
                "Penulis Menyetujui {$taskLabel}!",
                "Penulis {$user->name} telah memeriksa dan MENYETUJUI hasil pengerjaan {$taskLabel} untuk buku '{$submission->title}' ({$submission->code}).",
                'success',
                'submission',
                $submission->id
            );
        }

        return back()->with('success', "Alhamdulillah! Anda telah menyetujui hasil {$taskLabel}.");
    }

    public function revisionDesign(Request $request, DesignTask $task)
    {
        $user = Auth::user();
        $submission = $task->submission;

        if ($submission->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'notes' => ['required', 'string', 'min:5'],
        ], [
            'notes.required' => 'Catatan revisi wajib diisi agar desainer mengetahui bagian yang perlu diperbaiki.',
            'notes.min' => 'Catatan revisi minimal 5 karakter.',
        ]);

        $task->update([
            'author_approved' => false,
            'author_notes' => $validated['notes'],
        ]);

        $taskLabel = $task->task_type === 'layout' ? 'Layout Isi Buku' : 'Desain Cover';
        ActivityLog::record('design.author_revision', "Penulis meminta catatan revisi {$taskLabel} untuk '{$submission->title}'", $task, $user);

        NotificationService::notifyAdmins(
            "Catatan Revisi dari Penulis ({$taskLabel})",
            "Penulis {$user->name} memberikan catatan revisi pada {$taskLabel} buku '{$submission->title}': {$validated['notes']}",
            'warning',
            'submission',
            $submission->id
        );

        return back()->with('info', "Catatan revisi {$taskLabel} berhasil dikirimkan kepada tim redaksi & desainer.");
    }
}

