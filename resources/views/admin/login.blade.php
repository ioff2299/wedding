<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Административная панель</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:ital,wght@0,300;0,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="admin-body admin-login-page">

<div class="login-card">
    <div class="login-brand">Wedding Admin</div>
    <div class="login-subtitle jost">Панель управления</div>

    <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
        @csrf

        <div class="admin-field">
            <label class="admin-label jost">Email</label>
            <input
                type="email"
                name="email"
                class="admin-input jost"
                value="{{ old('email') }}"
                placeholder="admin@wedding.local"
                autofocus
                required
            >
        </div>

        <div class="admin-field">
            <label class="admin-label jost">Пароль</label>
            <input
                type="password"
                name="password"
                class="admin-input jost"
                placeholder="••••••••"
                required
            >
        </div>

        <button type="submit" class="btn-login jost">Войти</button>

        @if($errors->any())
        <div class="login-error jost">
            {{ $errors->first() }}
        </div>
        @endif
    </form>

    <p style="margin-top:24px; font-size:0.7rem; color:rgba(107,78,61,0.4); text-align:center;" class="jost">
        Доступ только для администраторов
    </p>
</div>

</body>
</html>
