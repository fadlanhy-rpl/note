<?php

namespace App\Http\Middleware;

// Gunakan alias 'BaseAuthenticate' untuk menghindari konflik nama jika ada
// kelas Authenticate lain dalam namespace yang sama (meskipun jarang terjadi).
// Atau cukup gunakan nama panjangnya: use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request; // Penting untuk type-hinting

class Authenticate extends BaseAuthenticate // Pastikan mewarisi dari middleware bawaan Laravel
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * Method ini dipanggil oleh parent class (Illuminate\Auth\Middleware\Authenticate)
     * ketika method handle() (yang diwarisi) mendeteksi bahwa user tidak terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request TIDAK mengharapkan respons JSON (artinya, ini adalah request browser biasa),
        // maka arahkan pengguna ke rute yang memiliki nama 'login'.
        // Pastikan Anda memiliki rute dengan ->name('login').
        if (! $request->expectsJson()) {
            return route('login');
        }

        // Jika request mengharapkan respons JSON (misalnya, dari API client atau AJAX),
        // kembalikan null. Laravel akan secara otomatis mengirimkan respons JSON 401 Unauthorized.
        return null;
    }
}