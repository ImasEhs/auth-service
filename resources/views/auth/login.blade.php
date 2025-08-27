<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .btn-animate {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-animate:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-animate:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl overflow-hidden flex flex-col md:flex-row">
        <!-- Panel Kiri (Biru) -->
        <div class="bg-blue-600 text-white p-8 md:p-12 w-full md:w-1/2 flex flex-col items-center justify-center text-center">
            <!-- Ganti dengan logo yang relevan -->
            <img src="{{asset('logo-pacitan.png')}}" alt="Logo" class="w-24 h-24 mb-6">
            <h1 class="text-3xl md:text-4xl font-semibold mb-2">Selamat Datang Kembali</h1>
            <p class="text-sm text-gray-200">Portal Layanan Kepegawaian BKPSDM Pacitan.</p>
        </div>

        <!-- Panel Kanan (Formulir Login) -->
        <div class="p-8 md:p-12 w-full md:w-1/2 text-center">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-2">Masuk ke Akun Anda</h2>
            <p class="text-sm text-gray-500 mb-6">Gunakan surel dan kata sandi Anda.</p>
            
            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="nip" class="block text-left text-sm font-medium text-gray-700">NIP</label>
                    <input type="text" id="nip" name="nip" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="password" class="block text-left text-sm font-medium text-gray-700">Kata Sandi</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-center justify-start">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                </div>
                
                <div id="message" class="text-sm text-red-500"></div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 btn-animate focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const nip = document.getElementById('nip').value;
            const password = document.getElementById('password').value;
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = ''; // Clear previous error messages

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ nip, password }),
                });

                const data = await response.json();

                if (response.ok) {
                    messageDiv.style.color = 'green';
                    messageDiv.textContent = 'Login berhasil! Mengalihkan...';

                    // Save token to localStorage or cookies
                    localStorage.setItem('sso_token', data.token);

                    // Get redirect URL from URL parameters or default
                    const redirectUrl = new URLSearchParams(window.location.search).get('redirect') || 'http://localhost';
                    window.location.href = redirectUrl + "?token=" + data.token;

                } else {
                    messageDiv.style.color = 'red';
                    messageDiv.textContent = data.message || 'Login gagal. Periksa NIP atau password.';
                }
            } catch (error) {
                messageDiv.style.color = 'red';
                messageDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
            }
        });
    </script>
</body>
</html>