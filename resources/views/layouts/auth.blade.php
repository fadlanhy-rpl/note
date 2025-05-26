<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MyNotes') - MyNotes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'note-yellow': '#fff475', // Warna aksen
                        // Tambahkan warna lain jika perlu
                    },
                    fontFamily: {
                        'google-sans': ['"Google Sans"', 'sans-serif'],
                        'roboto': ['Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa; /* Latar belakang abu-abu muda */
        }
        .auth-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .auth-title {
            font-family: 'Google Sans', sans-serif;
        }
        .form-input {
            border: 1px solid #dadce0;
            border-radius: 4px;
            padding: 12px 16px;
            transition: border-color 0.2s ease;
        }
        .form-input:focus {
            border-color: #4285f4; /* Google Blue */
            outline: none;
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
        }
        .btn-primary {
            background-color: #4285f4; /* Google Blue */
            color: white;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #3367d6;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-4">
        <div class="flex justify-center mb-8">
            <a href="{{ route('home') }}" class="flex items-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="ml-2 text-2xl font-google-sans font-medium text-gray-700">MyNotes</span>
            </a>
        </div>

        <div class="auth-card p-6 sm:p-8">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Oops! Something went wrong.</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status')) {{-- Untuk pesan seperti reset password link sent --}}
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>

        <div class="mt-6 text-center text-sm text-gray-600">
            @if (Route::currentRouteName() == 'login')
                Don't have an account? <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">Register here</a>
            @elseif (Route::currentRouteName() == 'register')
                Already have an account? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Login here</a>
            @endif
        </div>
         <p class="mt-8 text-center text-xs text-gray-500">
            © {{ date('Y') }} MyNotes. All rights reserved.
        </p>
    </div>

</body>
</html>