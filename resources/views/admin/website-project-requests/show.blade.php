<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Respon - {{ $response->customer_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $displayValue = function ($value) {
            if (is_array($value)) {
                return count($value) > 0 ? implode(', ', $value) : '-';
            }

            return filled($value) ? $value : '-';
        };
    @endphp

    <main class="admin-page">
        <section class="admin-shell admin-detail-shell" aria-labelledby="detail-title">
            <div class="admin-content">
                @if (session('success'))
                    <div class="admin-success-alert" role="status">{{ session('success') }}</div>
                @endif

                <div class="admin-topbar">
                    <div>
                        <a class="admin-back-link" href="{{ route('admin.website-project-requests.index') }}">Kembali ke halaman utama admin</a>
                        <h1 id="detail-title">{{ $response->customer_name }}</h1>
                        <p>{{ $response->business_name }} &middot; {{ $response->whatsapp_number }}</p>
                    </div>
                    <div class="admin-topbar-actions">
                        <form class="admin-detail-status-form" action="{{ route('admin.website-project-requests.update-status', $response) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="detail-status">Status respon</label>
                            <select id="detail-status" name="status" data-enhanced-select>
                                @foreach ($statusLabels as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}" @selected($response->status === $statusValue)>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Simpan status</button>
                        </form>
                    </div>
                </div>

                <div class="admin-detail-grid">
                    @foreach ($sections as $sectionTitle => $items)
                        <section class="admin-detail-card" aria-labelledby="section-{{ \Illuminate\Support\Str::slug($sectionTitle) }}">
                            <h2 id="section-{{ \Illuminate\Support\Str::slug($sectionTitle) }}">{{ $sectionTitle }}</h2>
                            <dl>
                                @foreach ($items as $item)
                                    <div>
                                        <dt>{{ $item['label'] }}</dt>
                                        <dd>{{ $displayValue($item['value']) }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </section>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</body>
</html>

