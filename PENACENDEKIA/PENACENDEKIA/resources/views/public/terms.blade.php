@extends('layouts.app')

@section('title', 'Syarat & Ketentuan Penerbitan Buku')

@section('content')
<div class="container py-5 max-w-4xl">
    <div class="text-center mb-5">
        <span class="badge badge-soft-primary px-3 py-2 rounded-pill fw-semibold mb-2">Peraturan & Pedoman</span>
        <h1 class="display-5 fw-bold font-serif mb-3">Syarat & Ketentuan Penerbitan</h1>
        <p class="text-muted">Pedoman resmi penerbitan buku mandiri di PT. Pena Cendikia.</p>
    </div>

    <div class="card card-soft p-4 p-md-5">
        <div class="d-flex flex-column gap-4 text-dark">
            <!-- 1 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">1</span>
                    <span>Keaslian Karya & Hak Cipta</span>
                </h5>
                <p class="text-muted small mb-0">
                    Penulis menjamin secara penuh bahwa naskah yang diajukan adalah karya orisinal, bukan hasil plagiarisme atau menyalin karya orang lain tanpa izin tertulis yang sah. Seluruh hak cipta intelektual tetap berada di tangan penulis seutuhnya. PT. Pena Cendikia dibebaskan dari segala tuntutan hukum pihak ketiga terkait sengketa hak cipta naskah.
                </p>
            </div>

            <!-- 2 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">2</span>
                    <span>Kelengkapan Identitas Penulis</span>
                </h5>
                <p class="text-muted small mb-0">
                    Untuk memenuhi kepatuhan hukum dan tertib administrasi penerbitan buku di Indonesia, penulis wajib mengisi data diri yang sah meliputi Nama Lengkap, Nomor Induk Kependudukan (NIK 16 digit), nomor telepon/WhatsApp aktif, serta mengunggah foto Kartu Tanda Penduduk (KTP) yang sah. Data diri dilindungi kerahasiaannya dan hanya dapat diakses oleh administrator sistem.
                </p>
            </div>

            <!-- 3 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">3</span>
                    <span>Format & Spesifikasi Berkas Naskah</span>
                </h5>
                <p class="text-muted small mb-0">
                    Berkas naskah wajib diunggah dalam format <strong>Microsoft Word (.docx)</strong> atau <strong>PDF (.pdf)</strong> dengan ukuran maksimal 50 MB. Jumlah halaman naskah minimal adalah <strong>40 halaman format buku A5</strong>. Penulis yang telah memiliki tata letak sendiri diperbolehkan melampirkan berkas layout tambahan.
                </p>
            </div>

            <!-- 4 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">4</span>
                    <span>Sistem Pembayaran Manual & Termin</span>
                </h5>
                <p class="text-muted small mb-2">
                    Pembayaran biaya penerbitan dilakukan secara <strong>transfer manual</strong> langsung ke rekening resmi bank perusahaan PT. Pena Cendikia dengan tahapan sebagai berikut:
                </p>
                <ul class="text-muted small mb-0 ps-3">
                    <li><strong>Termin 1 (DP 50%):</strong> Wajib ditransfer maksimal 3x24 jam setelah naskah disetujui admin untuk memulai proses desain layout dan cover.</li>
                    <li><strong>Termin 2 (Pelunasan 50%):</strong> Dilunasi setelah desain layout dan sampul disetujui bersama, sebelum buku masuk ke proses pencetakan fisik.</li>
                    <li>Penulis wajib mengunggah bukti transfer sah di dashboard. Verifikasi dilakukan secara manual oleh administrator.</li>
                </ul>
            </div>

            <!-- 5 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">5</span>
                    <span>Revisi Desain & Persetujuan Cetak</span>
                </h5>
                <p class="text-muted small mb-0">
                    Tim desainer PT. Pena Cendikia akan menyusun tata letak isi dan cover sesuai arahan penulis. Penulis berhak memberikan masukan revisi. Setelah desain disetujui (ACC Final) dan naskah masuk antrian cetak, perubahan isi naskah tidak dapat dilakukan kembali.
                </p>
            </div>

            <!-- 6 -->
            <div>
                <h5 class="fw-bold font-serif mb-2 text-primary d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 text-white" style="background-color: #8B5E3C; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">6</span>
                    <span>Pencetakan & Pengiriman Buku</span>
                </h5>
                <p class="text-muted small mb-0">
                    Minimal pemesanan cetak adalah <strong>10 eksemplar</strong>. Waktu produksi cetak fisik adalah 7 hingga 14 hari kerja setelah pelunasan diverifikasi. Buku yang selesai dicetak akan dikemas secara rapi dan dikirimkan ke alamat yang terdaftar di profil penulis.
                </p>
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('register') }}" class="btn btn-brand px-5 py-2">
                Saya Setuju & Ingin Mendaftar Sebagai Penulis
            </a>
        </div>
    </div>
</div>
@endsection