<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Kebutuhan Pembuatan Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $websiteTypes = [
            'Company profile',
            'Landing page',
            'Katalog produk atau layanan',
            'Toko online',
            'Website reservasi atau booking',
            'Sistem informasi atau dashboard',
            'Belum tahu, perlu konsultasi',
            'Lainnya',
        ];

        $websiteGoals = [
            'Memperkenalkan bisnis atau perusahaan',
            'Menampilkan produk atau layanan',
            'Mendapatkan calon pelanggan',
            'Menerima pesanan atau transaksi',
            'Menerima reservasi',
            'Mengelola data atau kegiatan operasional',
            'Lainnya',
        ];

        $targetUsers = [
            'Pelanggan',
            'Admin',
            'Pemilik usaha',
            'Karyawan',
            'Member atau pengguna terdaftar',
            'Lainnya',
        ];

        $requiredFeatures = [
            'Tombol WhatsApp',
            'Formulir kontak',
            'Katalog produk atau layanan',
            'Pencarian dan filter',
            'Login dan registrasi',
            'Dashboard admin',
            'Keranjang dan checkout',
            'Pembayaran online',
            'Reservasi atau booking',
            'Pengelolaan pesanan',
            'Laporan atau ekspor data',
            'Belum tahu, perlu rekomendasi',
            'Lainnya',
        ];

        $availableMaterials = [
            'Logo',
            'Teks atau deskripsi',
            'Foto',
            'Daftar produk atau layanan',
            'Daftar harga',
            'Belum tersedia',
            'Membutuhkan bantuan pembuatan materi',
        ];

        $budgetRanges = [
            'Di bawah Rp1.000.000',
            'Rp1.000.000-Rp3.000.000',
            'Rp3.000.000-Rp5.000.000',
            'Rp5.000.000-Rp10.000.000',
            'Di atas Rp10.000.000',
            'Belum menentukan anggaran',
        ];
    @endphp

    <main class="form-request-page">
        <section class="form-request-card" aria-labelledby="form-title">
            <a class="back-link" href="/">Kembali ke halaman utama</a>

            @if (session('success'))
                <div class="success-alert" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @error('submission')
                <div class="error-alert" role="alert">{{ $message }}</div>
            @enderror

            <div class="form-request-heading">
                <p class="eyebrow">Brief kebutuhan website</p>
                <h1 id="form-title">Form Kebutuhan Pembuatan Website</h1>
                <p>Form ini digunakan untuk memahami kebutuhan awal website Anda. Informasi yang diberikan akan menjadi dasar untuk menentukan konsep, fitur, estimasi biaya, dan waktu pengerjaan.</p>
            </div>

            <div class="form-request-layout">
                <aside class="form-guide" aria-labelledby="form-guide-title">
                    <p class="form-guide-label">Panduan pengisian</p>
                    <h2 id="form-guide-title">Ikuti 4 bagian berikut</h2>
                    <ol class="progress-steps" aria-label="Empat tahap formulir">
                        <li>
                            <span>1</span>
                            <div>
                                <a href="#informasi-customer">Customer</a>
                                <p>Isi identitas dan nomor WhatsApp aktif agar kami bisa menghubungi penanggung jawab.</p>
                            </div>
                        </li>
                        <li>
                            <span>2</span>
                            <div>
                                <a href="#kebutuhan-website">Kebutuhan</a>
                                <p>Jelaskan jenis website, tujuan, pengguna, alur pemakaian, dan fitur yang diharapkan.</p>
                            </div>
                        </li>
                        <li>
                            <span>3</span>
                            <div>
                                <a href="#desain-dan-materi">Desain</a>
                                <p>Tandai materi yang sudah tersedia dan tuliskan contoh gaya visual yang disukai.</p>
                            </div>
                        </li>
                        <li>
                            <span>4</span>
                            <div>
                                <a href="#waktu-dan-anggaran">Anggaran</a>
                                <p>Berikan target waktu dan kisaran budget agar estimasi awal bisa disusun realistis.</p>
                            </div>
                        </li>
                    </ol>
                </aside>

                <form class="request-form" action="/formulir" method="post" data-submission-form>
                    @csrf

                    <x-form.section
                        id="informasi-customer"
                        title="Informasi Customer"
                        description="Data kontak utama agar kami bisa meninjau dan menghubungi Anda kembali."
                    >
                        <x-form.input name="customer_name" label="Nama lengkap" required autocomplete="name" />
                        <x-form.input name="business_name" label="Nama bisnis, usaha, atau organisasi" required />
                        <x-form.input
                            name="whatsapp_number"
                            label="Nomor WhatsApp"
                            type="tel"
                            required
                            placeholder="Contoh: 081234567890"
                            autocomplete="tel"
                        />
                    </x-form.section>

                    <x-form.section
                        id="kebutuhan-website"
                        title="Kebutuhan Website"
                        description="Pilih jenis, tujuan, pengguna, alur, dan fitur yang paling menggambarkan kebutuhan awal."
                    >
                        <x-form.select
                            name="website_type"
                            label="Jenis website yang dibutuhkan"
                            :options="$websiteTypes"
                            required
                            data-other-select="website_type"
                        />

                        <div class="conditional-field" data-other-target="website_type" data-other-value="Lainnya">
                            <x-form.input name="website_type_other" label="Jenis website lainnya" />
                        </div>

                        <x-form.checkbox-group
                            name="website_goals"
                            label="Apa tujuan utama website tersebut?"
                            :options="$websiteGoals"
                            required
                            description="Bisa memilih lebih dari satu."
                        />

                        <div class="conditional-field" data-other-target="website_goals">
                            <x-form.input name="website_goal_other" label="Tujuan website lainnya" />
                        </div>

                        <x-form.checkbox-group
                            name="target_users"
                            label="Siapa pengguna utama website?"
                            :options="$targetUsers"
                            description="Bisa memilih lebih dari satu."
                        />

                        <div class="conditional-field" data-other-target="target_users">
                            <x-form.input name="target_user_other" label="Pengguna utama lainnya" />
                        </div>

                        <x-form.textarea
                            name="desired_workflow"
                            label="Jelaskan secara singkat alur penggunaan website yang diinginkan"
                            required
                            rows="5"
                            placeholder="Contoh: pelanggan melihat produk, memilih produk, mengisi data pesanan, lalu admin memproses pesanan."
                        />

                        <x-form.checkbox-group
                            name="required_features"
                            label="Fitur utama yang dibutuhkan"
                            :options="$requiredFeatures"
                            description="Bisa memilih lebih dari satu."
                        />

                        <div class="conditional-field" data-other-target="required_features">
                            <x-form.input name="required_feature_other" label="Fitur lainnya" />
                        </div>
                    </x-form.section>

                    <x-form.section
                        id="desain-dan-materi"
                        title="Desain dan Materi"
                        description="Beri gambaran materi yang sudah tersedia dan arah visual yang disukai."
                    >
                        <x-form.checkbox-group
                            name="available_materials"
                            label="Materi website yang sudah tersedia"
                            :options="$availableMaterials"
                        />

                        <x-form.textarea
                            name="design_reference"
                            label="Contoh website atau gaya desain yang disukai"
                            rows="4"
                            placeholder="Masukkan tautan website atau jelaskan gaya yang diinginkan, misalnya minimalis, modern, elegan, formal, atau ceria."
                        />
                    </x-form.section>

                    <x-form.section
                        id="waktu-dan-anggaran"
                        title="Waktu dan Anggaran"
                        description="Informasi ini membantu kami menyusun estimasi yang realistis sejak awal."
                    >
                        <x-form.input
                            name="target_completion_date"
                            label="Target website selesai"
                            type="date"
                            required
                            min="{{ now()->toDateString() }}"
                        />

                        <x-form.select
                            name="budget_range"
                            label="Kisaran anggaran"
                            :options="$budgetRanges"
                            required
                        />

                        <x-form.textarea
                            name="additional_information"
                            label="Informasi atau kebutuhan khusus lainnya"
                            rows="4"
                        />
                    </x-form.section>

                    <p class="submission-note">
                        Informasi pada formulir ini merupakan kebutuhan awal dan belum menjadi ruang lingkup pekerjaan yang final. Detail fitur, biaya, waktu pengerjaan, revisi, domain, hosting, dan pemeliharaan akan dibahas setelah formulir ditinjau.
                    </p>

                    <button class="submit-button" type="submit" data-submit-button>
                        <span data-submit-label>Kirim Kebutuhan Website</span>
                        <span class="loading-label" data-loading-label>Memproses...</span>
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
