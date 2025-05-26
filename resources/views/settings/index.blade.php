@extends('layouts.app')

@section('title', $pageTitle ?? 'Settings')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-google-sans font-medium text-gray-800 dark:text-gray-200">{{ $pageTitle ?? 'Application Settings' }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 space-y-8">
            <section>
                <h2 class="text-lg font-google-sans font-medium text-gray-700 dark:text-gray-300 mb-4 border-b dark:border-gray-700 pb-2">
                    Appearance
                </h2>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Choose how MyNotes looks to you. Select a single theme, or sync with your system preference.
                    </p>
                    <form id="themeForm">
                        @csrf
                        <fieldset>
                            <legend class="sr-only">Theme preference</legend>
                            <div class="space-y-2">
                                <label for="theme-light"
                                    class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="radio" name="theme_preference" id="theme-light" value="light"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-600 dark:ring-offset-gray-800">
                                    <span class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Light</span>
                                </label>
                                <label for="theme-dark"
                                    class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="radio" name="theme_preference" id="theme-dark" value="dark"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-600 dark:ring-offset-gray-800">
                                    <span class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Dark</span>
                                </label>
                                <label for="theme-system"
                                    class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="radio" name="theme_preference" id="theme-system" value="system"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-600 dark:ring-offset-gray-800">
                                    <span class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">System Preference</span>
                                </label>
                            </div>
                        </fieldset>
                    </form>
                    <div id="themeUpdateStatus" class="text-xs mt-2"></div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeRadios = document.querySelectorAll('input[name="theme_preference"]');
            const themeUpdateStatus = document.getElementById('themeUpdateStatus');
            const htmlElement = document.documentElement;

            // Fungsi untuk mendapatkan tema yang tersimpan
            function getCurrentTheme() {
                // Prioritas: localStorage > server value
                const localTheme = localStorage.getItem('theme');
                const serverTheme = "{{ $currentTheme ?? 'system' }}";
                return localTheme || serverTheme;
            }

            // Fungsi untuk menerapkan tema secara visual
            function applyThemeOnPage(theme) {
                console.log('[Settings JS] Applying theme:', theme);
                
                htmlElement.classList.remove('light', 'dark');
                
                if (theme === 'dark') {
                    htmlElement.classList.add('dark');
                } else if (theme === 'light') {
                    // Light mode - remove dark class
                    htmlElement.classList.remove('dark');
                } else if (theme === 'system') {
                    // System preference
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        htmlElement.classList.add('dark');
                    } else {
                        htmlElement.classList.remove('dark');
                    }
                }
                
                console.log('[Settings JS] HTML classes after theme application:', htmlElement.className);
            }

            // Fungsi untuk update radio button
            function updateRadioButtons(theme) {
                const targetRadio = document.getElementById('theme-' + theme);
                if (targetRadio) {
                    targetRadio.checked = true;
                    console.log('[Settings JS] Radio button updated to:', theme);
                } else {
                    console.warn('[Settings JS] Radio button not found for theme:', theme);
                }
            }

            // Fungsi untuk menyimpan preferensi tema
            function saveThemePreference(theme) {
                console.log('[Settings JS] Saving theme preference:', theme);
                
                // Update localStorage
                localStorage.setItem('theme', theme);
                
                // Update radio button
                updateRadioButtons(theme);
                
                // Apply theme visually
                applyThemeOnPage(theme);

                // Send to server
                fetch("{{ route('settings.theme.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ theme: theme })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatusMessage('Theme preference saved!', 'success');
                    } else {
                        showStatusMessage('Failed to save theme preference', 'error');
                    }
                })
                .catch(error => {
                    console.error('[Settings JS] Error saving theme:', error);
                    showStatusMessage('Error saving theme preference', 'error');
                });
            }

            // Fungsi untuk menampilkan pesan status
            function showStatusMessage(message, type = 'success') {
                if (themeUpdateStatus) {
                    themeUpdateStatus.textContent = message;
                    themeUpdateStatus.className = type === 'success' 
                        ? 'text-xs text-green-600 dark:text-green-400 mt-2 animate-pulse'
                        : 'text-xs text-red-600 dark:text-red-400 mt-2 animate-pulse';
                    
                    setTimeout(() => {
                        themeUpdateStatus.textContent = '';
                        themeUpdateStatus.classList.remove('animate-pulse');
                    }, 3000);
                }
            }

            // Event listener untuk radio buttons
            themeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        console.log('[Settings JS] Theme changed to:', this.value);
                        saveThemePreference(this.value);
                    }
                });
            });

            // Inisialisasi saat halaman dimuat
            function initializeTheme() {
                const currentTheme = getCurrentTheme();
                console.log('[Settings JS] Initializing with theme:', currentTheme);
                
                // Update radio button
                updateRadioButtons(currentTheme);
                
                // Apply theme (ini penting untuk sinkronisasi visual)
                applyThemeOnPage(currentTheme);
            }

            // Listener untuk perubahan sistem
            if (window.matchMedia) {
                const systemThemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                systemThemeQuery.addEventListener('change', function(e) {
                    const currentSelectedTheme = getCurrentTheme();
                    if (currentSelectedTheme === 'system') {
                        console.log('[Settings JS] System theme changed, reapplying system preference');
                        applyThemeOnPage('system');
                    }
                });
            }

            // Jalankan inisialisasi
            initializeTheme();

            // Sinkronisasi jika ada perubahan dari halaman lain
            window.addEventListener('storage', function(e) {
                if (e.key === 'theme') {
                    console.log('[Settings JS] Theme changed from another tab:', e.newValue);
                    const newTheme = e.newValue || 'system';
                    updateRadioButtons(newTheme);
                    applyThemeOnPage(newTheme);
                }
            });
        });
    </script>
@endpush

@push('styles')
    {{-- Custom styles untuk halaman settings jika diperlukan --}}
@endpush