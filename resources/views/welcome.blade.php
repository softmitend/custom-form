<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MadeYours</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="landing-shell">
        <section class="landing-card" aria-labelledby="hero-title">
            <div class="hero-band">
                <!-- <div class="eyebrow badge badge-light">Website custom untuk bisnis yang siap tumbuh</div> -->
                <h1 id="hero-title">Make Your Brief</h1>
                <p class="hero-copy">
                    Isi brief singkat, ceritakan tujuan bisnismu, lalu kami bantu susun website custom yang rapi,
                    responsif, mudah dipakai, dan siap mendukung penjualan.
                </p>
            </div>

            <div class="content-panel">
                <aside class="info-panel" id="benefit" aria-label="Benefit website custom">
                    <p class="panel-kicker">Kenapa custom?</p>
                    <ul class="benefit-list">
                        <li>
                            <span class="benefit-icon">01</span>
                            <span>Website tampil beda dan terasa lebih profesional di mata calon pelanggan.</span>
                        </li>
                        <li>
                            <span class="benefit-icon">02</span>
                            <span>Struktur halaman bisa disesuaikan untuk katalog, booking, portfolio, atau lead form.</span>
                        </li>
                        <li>
                            <span class="benefit-icon">03</span>
                            <span>Lebih mudah dikembangkan ketika bisnismu butuh fitur baru.</span>
                        </li>
                    </ul>
                    <div class="panel-bubble" aria-hidden="true"></div>
                </aside>

                <section class="cta-panel" id="proses" aria-label="Ajakan membuat website custom">
                    <div>
                        <span class="pill">Isi form. Mulai dari brief singkat</span>
                        <h2>Luangkan beberapa menit untuk mengisi kebutuhan websitemu sekarang.</h2>
                        <p>
                            Jawabanmu membantu kami memahami tujuan, jenis halaman, fitur prioritas,
                            dan arah visual yang paling cocok untuk brand-mu.
                        </p>
                    </div>

                    <div class="website-preview" aria-label="Mockup website custom">
                        <div class="preview-toolbar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="preview-grid">
                            <div class="preview-hero"></div>
                            <div class="preview-lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="preview-card aqua"></div>
                            <div class="preview-card pink"></div>
                            <div class="preview-card blue"></div>
                        </div>
                    </div>

                    <div class="cta-actions">
                        <a class="primary-cta" href="/formulir">Buat websitemu sekarang</a>
                    </div>
                </section>
            </div>

            <footer class="site-footer">
                <span>Developed by @acalysis (x account)</span>
            </footer>
        </section>
    </main>
</body>
</html>
