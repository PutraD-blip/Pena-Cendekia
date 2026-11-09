<section class="pt-3 pb-5" id="karya">
    <div class="container pt-0 pb-4">
        <div class="text-center mb-5">
            <span class="badge badge-soft-primary px-3 py-2 rounded-pill fw-semibold mb-2">Karya Terbitan</span>
            <h2 class="display-6 fw-bold font-serif mb-2">Sebagian Buku yang Telah Kami Terbitkan</h2>
        </div>
        <div class="row g-3 mb-5 pb-3">
            @foreach([
                ['Manajemen Kesiswaan', 'Buku ajar • 2025'],
                ['Manajemen Tata Usaha di Sekolah', 'Buku ajar • 2024'],
                ['Manajemen Sarana Prasarana', 'Buku ajar • 2023'],
                ['Pengembangan E-Modul Interaktif', 'Media pembelajaran AUD • 2024'],
            ] as [$title, $meta])
                <div class="col-md-6 col-lg-3">
                    <div class="card card-soft book-card p-4 h-100">
                        <h6 class="fw-bold font-serif mb-1">{{ $title }}</h6>
                        <small class="text-muted">{{ $meta }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mb-4">
            <span class="badge badge-soft-primary px-3 py-2 rounded-pill fw-semibold mb-2">Testimoni</span>
            <h2 class="display-6 fw-bold font-serif mb-2">Kata Para Penulis</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['Pena Cendekia memberikan pelayanan yang sangat baik dalam menerbitkan buku dan biaya penerbitan sangat terjangkau di saku.', 'Yuni Ernawati', 'Guru', 'images/Yuni E.png'],
                ['Berkah bagi kami menemukan penerbit Pena Cendekia. Kualitas, pelayanan memuaskan menyenangkan hati.', 'Reiza Praselanova', 'Motivator', 'images/reiza.jpg'],
                ['Prosesnya sangat cepat, pelayanannya bagus, desain cover dan layout isi sangat bagus, harganya sangat terjangkau.', 'Dr. Lilik Huriyah, M.Pd.I', 'Dosen UIN Sunan Ampel Surabaya', 'images/lilik huriyah.jpg'],
            ] as [$quote, $name, $role, $photo])
                <div class="col-md-4">
    <div class="card card-soft p-4 h-100 testimonial-card">

        <img
            src="{{ asset($photo) }}"
            alt="Foto {{ $name }}"
            class="testimonial-photo"
        >

        <i class="bi bi-quote fs-1 mb-1" style="color: var(--brand-secondary);"></i>

        <p class="text-muted small">{{ $quote }}</p>

        <div class="mt-auto">
            <span class="fw-bold d-block">{{ $name }}</span>
            <small class="text-muted">{{ $role }}</small>
        </div>

    </div>
</div>
            @endforeach
        </div>
    </div>
</section>
