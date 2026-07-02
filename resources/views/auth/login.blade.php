<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบ | PSPP Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: sans-serif;
            background: #f5f6fa;
        }

        .card {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
            position: relative;
        }

        .home-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background: #6b7280;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .home-btn:hover {
            background: #4b5563;
        }

        .logo {
            display: block;
            margin: 0 auto 10px auto;
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 72px;
        }

        .toggle-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-20%);
            width: auto;
            margin: 0;
            padding: 5px 8px;
            background: transparent;
            color: #2563eb;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .toggle-password:hover {
            background: transparent;
            color: #1e40af;
        }

        button[type="submit"] {
            background: #2563eb;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #1e40af;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="card">

    <a href="/" class="home-btn">HOME</a>

    <img src="{{ asset('images/logoBitpps.png') }}" class="logo">

    <h2>เข้าสู่ระบบ</h2>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>Email</label>
        <input type="email" name="email" required>

        <label>รหัสผ่าน</label>

        <div class="password-wrap">
            <input
                id="password"
                type="password"
                name="password"
                required>

            <button
                type="button"
                class="toggle-password"
                onclick="togglePassword('password', this)">
                แสดง
            </button>
        </div>

        <button type="submit">
            เข้าสู่ระบบ
        </button>
    </form>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'ซ่อน';
        } else {
            input.type = 'password';
            button.textContent = 'แสดง';
        }
    }
</script>

</body>
</html>