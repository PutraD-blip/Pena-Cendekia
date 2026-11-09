<section class="pt-3 pb-5" id="alur">
    <div class="container pt-0 pb-4">
        <div class="text-center mb-5">
            <span class="badge badge-soft-primary px-3 py-2 rounded-pill fw-semibold mb-2">Alur Kerja</span>
            <h2 class="display-6 fw-bold font-serif mb-2">Transparan dan Mudah Dipantau</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['Kirim Naskah', 'Penulis mengisi data dan mengunggah naskah lewat portal.'],
                ['Review Admin', 'Naskah diperiksa, lalu rincian biaya dikirim ke penulis.'],
                ['DP & Desain', 'Setelah DP 50% terverifikasi, desainer mengerjakan layout dan sampul.'],
                ['Pelunasan & Cetak', 'Desain disetujui, pelunasan 50%, lalu buku dicetak dan dikirim.'],
            ] as $i => [$title, $desc])
                <div class="col-md-6 col-lg-3">
                    <div class="card card-soft p-4 h-100">
                        <span class="step-num d-inline-flex align-items-center justify-content-center rounded-circle text-white fw-bold small mb-3">{{ $i + 1 }}</span>
                        <h5 class="fw-bold font-serif mb-2">{{ $title }}</h5>
                        <p class="text-muted small mb-0">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
