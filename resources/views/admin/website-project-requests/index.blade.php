<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Respon Form Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="admin-page">
        <section class="admin-shell" aria-labelledby="admin-title">
            <div class="admin-content">
                <div class="admin-topbar">
                    <div>
                        <a class="admin-back-link" href="{{ url('/') }}">Halaman utama</a>
                        <h1 id="admin-title">Dashboard Respon Form</h1>
                        <p>Pantau semua kebutuhan website yang masuk dari halaman formulir.</p>
                    </div>
                    <div class="admin-topbar-actions">
                        <a class="admin-primary-link" href="{{ route('website-project-requests.create') }}">Buka form</a>
                        <form action="{{ route('admin.logout') }}" method="post">
                            @csrf
                            <button class="admin-secondary-button" type="submit">Logout</button>
                        </form>
                    </div>
                </div>

                <div class="admin-stat-grid" aria-label="Ringkasan respon">
                    <article class="admin-stat-card admin-stat-card-primary">
                        <div class="admin-stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z" />
                                <path d="M8 8h8M8 12h8M8 16h4" />
                            </svg>
                        </div>
                        <div>
                            <span>Total respon</span>
                            <strong>{{ number_format($stats['total']) }}</strong>
                        </div>
                    </article>
                    <article class="admin-stat-card">
                        <div class="admin-stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                                <path d="M12 7v5l3 2" />
                            </svg>
                        </div>
                        <div>
                            <span>Status baru</span>
                            <strong>{{ number_format($stats['new']) }}</strong>
                        </div>
                    </article>
                    <article class="admin-stat-card">
                        <div class="admin-stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M7 3v3M17 3v3M4 9h16" />
                                <path d="M6.5 5h11A2.5 2.5 0 0 1 20 7.5v10A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-10A2.5 2.5 0 0 1 6.5 5Z" />
                                <path d="m9 14 2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <span>Masuk hari ini</span>
                            <strong>{{ number_format($stats['today']) }}</strong>
                        </div>
                    </article>
                    <article class="admin-stat-card">
                        <div class="admin-stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M7 3v3M17 3v3M4 9h16" />
                                <path d="M6.5 5h11A2.5 2.5 0 0 1 20 7.5v10A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-10A2.5 2.5 0 0 1 6.5 5Z" />
                                <path d="M8 13h2M12 13h2M16 13h.01M8 17h2M12 17h2M16 17h.01" />
                            </svg>
                        </div>
                        <div>
                            <span>Bulan ini</span>
                            <strong>{{ number_format($stats['this_month']) }}</strong>
                        </div>
                    </article>
                </div>

                <section class="admin-panel" aria-labelledby="response-list-title">
                    @if (session('success'))
                        <div class="admin-success-alert" role="status">{{ session('success') }}</div>
                    @endif

                    <div class="admin-panel-heading">
                        <div>
                            <h2 id="response-list-title">Daftar respon</h2>
                            <p>Klik salah satu respon untuk membaca detail lengkapnya.</p>
                        </div>
                        <form class="admin-filter-form admin-filter-form-basic" action="{{ route('admin.website-project-requests.index') }}" method="get">
                            <div class="input-group input-group-sm admin-filter-group">
                                <span class="input-group-text">Cari</span>
                                <input class="form-control" type="search" name="search" value="{{ $search }}" placeholder="Nama, bisnis, WhatsApp">
                            </div>
                            <div class="input-group input-group-sm admin-filter-group admin-filter-status-group">
                                <span class="input-group-text">Status</span>
                                <select class="form-select" name="status" data-enhanced-select>
                                    <option value="">Semua status</option>
                                    @foreach ($statusLabels as $statusValue => $statusLabel)
                                        <option value="{{ $statusValue }}" @selected($status === $statusValue)>{{ $statusLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit">Filter</button>
                            @if ($search !== '' || $status !== '')
                                <a class="admin-filter-reset" href="{{ route('admin.website-project-requests.index') }}">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Jenis Website</th>
                                    <th>Anggaran</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Dikirim</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($responses as $response)
                                    <tr>
                                        <td data-label="Customer">
                                            <strong>{{ $response->customer_name }}</strong>
                                            <span>{{ $response->business_name }}</span>
                                            <span>{{ $response->whatsapp_number }}</span>
                                        </td>
                                        <td data-label="Jenis Website">{{ $response->website_type }}</td>
                                        <td data-label="Anggaran">{{ $response->budget_range }}</td>
                                        <td data-label="Target">{{ $response->target_completion_date?->format('d M Y') }}</td>
                                        <td data-label="Status">
                                            <form class="admin-status-form" action="{{ route('admin.website-project-requests.update-status', $response) }}" method="post">
                                                @csrf
                                                @method('PATCH')
                                                <label class="sr-only" for="status-{{ $response->id }}">Ubah status</label>
                                                <select id="status-{{ $response->id }}" name="status" data-enhanced-select onchange="this.form.submit()">
                                                    @foreach ($statusLabels as $statusValue => $statusLabel)
                                                        <option value="{{ $statusValue }}" @selected($response->status === $statusValue)>{{ $statusLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td data-label="Dikirim">{{ $response->created_at?->format('d M Y, H:i') }}</td>
                                        <td data-label="Aksi">
                                            <a class="admin-row-link" href="{{ route('admin.website-project-requests.show', $response) }}">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="admin-empty-state">
                                                <strong>Belum ada respon.</strong>
                                                <span>Data akan muncul setelah customer mengirim formulir.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="admin-pagination">
                        {{ $responses->links() }}
                    </div>
                </section>
            </div>
        </section>
    </main>
</body>
</html>
