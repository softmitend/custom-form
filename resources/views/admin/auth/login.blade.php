<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="admin-login-page">
        <section class="admin-login-card" aria-labelledby="login-title">
            <a class="admin-back-link" href="{{ url('/') }}">Halaman utama</a>
            <p class="eyebrow">Admin area</p>
            <h1 id="login-title">Masuk ke Dashboard</h1>
            <p class="admin-login-copy">Gunakan akun admin untuk membaca dan mengelola respon formulir website.</p>

            <form class="admin-login-form" action="{{ route('admin.login.store') }}" method="post">
                @csrf

                <div class="admin-login-field">
                    <label for="email">Email admin</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-login-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                    @error('password')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <label class="admin-login-check">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya</span>
                </label>

                <button type="submit">Login Admin</button>
            </form>
        </section>
    </main>
</body>
</html>
