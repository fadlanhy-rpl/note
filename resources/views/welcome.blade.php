<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyNotes - Think, plan, and organize all in one place</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'note-yellow': '#fff475',
                        'note-green': '#ccff90',
                        'note-blue': '#cbf0f8',
                        'note-purple': '#d7aefb',
                        'note-pink': '#fdcfe8',
                        'note-orange': '#fbbc04',
                        'note-teal': '#a7ffeb',
                        'note-red': '#f28b82',
                    },
                    fontFamily: {
                        'google-sans': ['"Google Sans"', 'sans-serif'],
                        'roboto': ['Roboto', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out infinite 2s',
                        'float-delayed-2': 'float 6s ease-in-out infinite 4s',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6, .font-google-sans {
            font-family: 'Google Sans', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .note-card {
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .note-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .floating-element {
            position: absolute;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #4f46e5;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: #4f46e5;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary {
            transition: all 0.3s ease;
            border: 2px solid #4f46e5;
        }

        .btn-secondary:hover {
            background-color: #4f46e5;
            color: white;
            transform: translateY(-2px);
        }

        .feature-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .integration-icon {
            transition: all 0.3s ease;
        }

        .integration-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        @media (max-width: 768px) {
            .floating-element {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-xl font-google-sans font-semibold text-gray-900">MyNotes</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Features</a>
                    <a href="#solutions" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Solutions</a>
                    <a href="#integrations" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Integrations</a>
                    {{-- <a href="#pricing" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Pricing</a> --}}
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="hidden sm:block text-gray-600 hover:text-gray-900 font-medium transition-colors">Sign in</a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">Get Started</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-600 hover:text-gray-900" id="mobile-menu-btn">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                <a href="#features" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Features</a>
                <a href="#solutions" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Solutions</a>
                <a href="#integrations" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Integrations</a>
                <a href="#pricing" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Pricing</a>
                <a href="#" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Sign in</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient relative overflow-hidden min-h-screen flex items-center">
        <!-- Floating Elements -->
        <div class="floating-element animate-float top-20 left-10 w-48 h-32 bg-note-yellow p-4 transform rotate-3">
            <div class="text-sm text-gray-800 font-medium">Take notes on</div>
            <div class="text-sm text-gray-800">ideas or create checklists</div>
            <div class="text-sm text-gray-800">and accomplish more</div>
            <div class="text-sm text-gray-800 font-medium">tasks with ease.</div>
            <div class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></div>
        </div>

        <div class="floating-element animate-float-delayed top-32 right-16 w-40 h-28 bg-white p-3 shadow-lg">
            <div class="flex items-center space-x-2 mb-2">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-800">Reminders</span>
            </div>
            <div class="text-xs text-gray-600">Today's Meeting</div>
            <div class="text-xs text-gray-500 mt-1">📅 10:00 AM</div>
        </div>

        <div class="floating-element animate-float-delayed-2 bottom-32 left-16 w-44 h-36 bg-white p-3 shadow-lg">
            <div class="text-xs font-medium text-gray-800 mb-2">Today's tasks</div>
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-sm"></div>
                    <span class="text-xs text-gray-700">New ideas for campaign</span>
                    <div class="w-5 h-5 bg-gray-200 rounded-full"></div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-sm"></div>
                    <span class="text-xs text-gray-700">Design PPT #3</span>
                    <div class="w-5 h-5 bg-gray-200 rounded-full"></div>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">75% complete</div>
            <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                <div class="bg-blue-500 h-1 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <div class="floating-element animate-float bottom-20 right-20 w-36 h-32 bg-white p-3 shadow-lg">
            <div class="text-xs font-medium text-gray-800 mb-2">100+ integrations</div>
            <div class="grid grid-cols-3 gap-2">
                <div class="w-8 h-8 bg-red-500 rounded flex items-center justify-center text-white text-xs font-bold">G</div>
                <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center text-white text-xs font-bold">S</div>
                <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center text-white text-xs font-bold">T</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-7xl font-google-sans font-bold text-gray-900 mb-6">
                    Think, plan, and
                    <span class="block text-gray-600">organize</span>
                    <span class="block text-gray-400">all in one place</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto animate-slide-up">
                    Efficiently capture your thoughts, manage your tasks and boost productivity with MyNotes.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-scale-in">
                    <button class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                        Get free demo
                    </button>
                    <button class="btn-secondary text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg">
                        Watch video
                    </button>
                </div>
            </div>
        </div>

        <!-- Decorative dots -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-0">
            <div class="grid grid-cols-4 gap-4 opacity-20">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-800 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-800 rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4">
                    Everything you need to stay organized
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From simple notes to complex projects, MyNotes adapts to your workflow and helps you achieve more.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200">
                    <div class="w-12 h-12 bg-note-yellow rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-google-sans font-semibold text-gray-900 mb-3">Quick Notes</h3>
                    <p class="text-gray-600">Capture ideas instantly with our intuitive note-taking interface. Never lose a thought again.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200">
                    <div class="w-12 h-12 bg-note-purple rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-google-sans font-semibold text-gray-900 mb-3">Smart Labels</h3>
                    <p class="text-gray-600">Organize your notes with intelligent labeling system that learns from your habits.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200">
                    <div class="w-12 h-12 bg-note-teal rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-google-sans font-semibold text-gray-900 mb-3">Reminders</h3>
                    <p class="text-gray-600">Set smart reminders and never miss important tasks or deadlines again.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4">
                    See MyNotes in action
                </h2>
                <p class="text-xl text-gray-600">
                    Experience the power of organized thinking
                </p>
            </div>

            <!-- Demo Notes Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                <!-- Note 1 -->
                <div class="note-card bg-note-teal p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-2">Project Ideas</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        • Website redesign<br>
                        • Mobile app concept<br>
                        • User research
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>2h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">work</span>
                        </div>
                    </div>
                </div>

                <!-- Note 2 -->
                <div class="note-card bg-note-purple p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-2">Life Goals</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Learn new skills, travel more, and maintain work-life balance.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>1d ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">personal</span>
                        </div>
                    </div>
                </div>

                <!-- Note 3 -->
                <div class="note-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">Meeting Notes</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Discussed Q4 goals and upcoming product launch timeline.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>3h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">important</span>
                        </div>
                    </div>
                </div>

                <!-- Note 4 -->
                <div class="note-card bg-note-yellow p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-2">Shopping List</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        • Groceries<br>
                        • Office supplies<br>
                        • Birthday gift
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>5h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs">personal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integrations Section -->
    <section id="integrations" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4">
                Integrates with your favorite tools
            </h2>
            <p class="text-xl text-gray-600 mb-12">
                Connect MyNotes with 100+ apps and services you already use
            </p>

            <div class="flex flex-wrap justify-center items-center gap-8">
                <!-- Integration Icons -->
                <div class="integration-icon w-16 h-16 bg-red-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">G</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">S</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">T</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">E</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">N</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-pink-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">F</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-google-sans font-bold text-white mb-4">
                Ready to get organized?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join thousands of users who have transformed their productivity with MyNotes
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-colors">
                    Start free trial
                </button>
                <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-indigo-600 transition-colors">
                    Schedule demo
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-xl font-google-sans font-semibold">MyNotes</span>
                    </div>
                    <p class="text-gray-400">
                        The ultimate note-taking and organization platform for modern professionals.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Community</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 MyNotes. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });

        // GSAP animations for floating elements
        gsap.registerPlugin();

        // Animate floating elements on scroll
        gsap.to('.floating-element', {
            y: -50,
            duration: 2,
            ease: "power2.inOut",
            repeat: -1,
            yoyo: true,
            stagger: 0.5
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero-gradient');
            const speed = scrolled * 0.5;
            
            if (parallax) {
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });

        // Note cards hover animation
        document.querySelectorAll('.note-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                gsap.to(this, {
                    scale: 1.05,
                    y: -10,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            card.addEventListener('mouseleave', function() {
                gsap.to(this, {
                    scale: 1,
                    y: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Integration icons animation
        document.querySelectorAll('.integration-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                gsap.to(this, {
                    scale: 1.2,
                    rotation: 10,
                    duration: 0.3,
                    ease: "back.out(1.7)"
                });
            });

            icon.addEventListener('mouseleave', function() {
                gsap.to(this, {
                    scale: 1,
                    rotation: 0,
                    duration: 0.3,
                    ease: "back.out(1.7)"
                });
            });
        });

        // Typing animation for hero text
        function typeWriter(element, text, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            
            type();
        }

        // Initialize typing animation when page loads
        window.addEventListener('load', () => {
            const heroTitle = document.querySelector('h1');
            if (heroTitle) {
                const originalText = heroTitle.textContent;
                setTimeout(() => {
                    typeWriter(heroTitle, originalText, 50);
                }, 1000);
            }
        });

        // Add scroll-triggered animations
        const scrollElements = document.querySelectorAll('.feature-card, .note-card');

        const elementInView = (el, dividend = 1) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
            );
        };

        const displayScrollElement = (element) => {
            element.classList.add('animate-slide-up');
        };

        const hideScrollElement = (element) => {
            element.classList.remove('animate-slide-up');
        };

        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 1.25)) {
                    displayScrollElement(el);
                } else {
                    hideScrollElement(el);
                }
            });
        };

        window.addEventListener('scroll', handleScrollAnimation);
    </script>
</body>
</html>