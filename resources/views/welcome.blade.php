<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyNotes - Think, plan, and organize all in one place</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
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
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate',
                        'rotate-slow': 'rotateSlow 20s linear infinite',
                        'wiggle': 'wiggle 1s ease-in-out infinite',
                        'typewriter': 'typewriter 4s steps(40) 1s 1 normal both',
                        'blink': 'blink 1s infinite',
                        'morphing': 'morphing 8s ease-in-out infinite',
                        'gradient-shift': 'gradientShift 6s ease-in-out infinite',
                        'floating-particles': 'floatingParticles 15s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(2deg)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(50px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        pulseGlow: {
                            '0%': { boxShadow: '0 0 20px rgba(79, 70, 229, 0.3)' },
                            '100%': { boxShadow: '0 0 40px rgba(79, 70, 229, 0.6)' },
                        },
                        rotateSlow: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' },
                        },
                        wiggle: {
                            '0%, 7%': { transform: 'rotateZ(0)' },
                            '15%': { transform: 'rotateZ(-15deg)' },
                            '20%': { transform: 'rotateZ(10deg)' },
                            '25%': { transform: 'rotateZ(-10deg)' },
                            '30%': { transform: 'rotateZ(6deg)' },
                            '35%': { transform: 'rotateZ(-4deg)' },
                            '40%, 100%': { transform: 'rotateZ(0)' },
                        },
                        typewriter: {
                            '0%': { width: '0' },
                            '100%': { width: '100%' },
                        },
                        blink: {
                            '0%, 50%': { borderColor: 'transparent' },
                            '51%, 100%': { borderColor: '#4f46e5' },
                        },
                        morphing: {
                            '0%, 100%': { borderRadius: '20px' },
                            '25%': { borderRadius: '50px' },
                            '50%': { borderRadius: '10px' },
                            '75%': { borderRadius: '30px' },
                        },
                        gradientShift: {
                            '0%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                            '100%': { backgroundPosition: '0% 50%' },
                        },
                        floatingParticles: {
                            '0%': { transform: 'translateY(100vh) rotate(0deg)' },
                            '100%': { transform: 'translateY(-100vh) rotate(360deg)' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6, .font-google-sans {
            font-family: 'Google Sans', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(-45deg, #f8fafc, #e2e8f0, #f1f5f9, #e7e5e4);
            background-size: 400% 400%;
            animation: gradientShift 8s ease-in-out infinite;
        }

        .note-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-origin: center;
            position: relative;
            overflow: hidden;
        }

        .note-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .note-card:hover::before {
            left: 100%;
        }

        .note-card:hover {
            transform: translateY(-15px) scale(1.05) rotateY(5deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .floating-element {
            position: absolute;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .floating-element:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            color: #4f46e5;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 50%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid #4f46e5;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #4f46e5;
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-secondary:hover::before {
            left: 0;
        }

        .btn-secondary:hover {
            color: white;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.3);
        }

        .feature-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(79, 70, 229, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .integration-icon {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .integration-icon::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57, #ff9ff3);
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .integration-icon:hover::before {
            opacity: 1;
        }

        .integration-icon:hover {
            transform: scale(1.2) rotate(10deg) translateY(-5px);
        }

        .particles-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(79, 70, 229, 0.3);
            border-radius: 50%;
            animation: floatingParticles 15s linear infinite;
        }

        .typewriter {
            overflow: hidden;
            border-right: 3px solid #4f46e5;
            white-space: nowrap;
            animation: typewriter 4s steps(40) 1s 1 normal both, blink 1s infinite;
        }

        .morphing-shape {
            animation: morphing 8s ease-in-out infinite;
        }

        .glow-effect {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }

        .rotating-element {
            animation: rotateSlow 20s linear infinite;
        }

        .wiggle-element {
            animation: wiggle 2s ease-in-out infinite;
        }

        .parallax-element {
            transform-style: preserve-3d;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(45deg, #4f46e5, #7c3aed, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
            background-size: 200% 200%;
        }

        .magnetic-element {
            transition: transform 0.3s ease;
        }

        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
            transform-origin: left;
            transform: scaleX(0);
            z-index: 9999;
        }

        .reveal-animation {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .reveal-animation.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-animation {
            transition-delay: calc(var(--stagger) * 0.1s);
        }

        @media (max-width: 768px) {
            .floating-element {
                display: none;
            }
            
            .note-card:hover {
                transform: translateY(-10px) scale(1.03);
            }
        }

        .cursor-trail {
            position: fixed;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.3), transparent);
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.1s ease;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: opacity 0.5s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            /* transform: translateX(300px); */
            translate: 2.5rem;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="text-center">
            <div class="loader mb-4 "></div>
            <p class="text-white text-lg font-google-sans">Loading MyNotes...</p>
        </div>
    </div>

    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Cursor Trail -->
    <div class="cursor-trail" id="cursorTrail"></div>

    <!-- Particles Container -->
    <div class="particles-container" id="particles-js"></div>

    <!-- Navigation -->
    <nav class="glass-effect sticky top-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2 magnetic-element">
                    <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center morphing-shape glow-effect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white wiggle-element" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-xl font-google-sans font-semibold gradient-text">MyNotes</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="nav-link text-gray-600 hover:text-gray-900 font-medium magnetic-element">Features</a>
                    <a href="#solutions" class="nav-link text-gray-600 hover:text-gray-900 font-medium magnetic-element">Solutions</a>
                    <a href="#integrations" class="nav-link text-gray-600 hover:text-gray-900 font-medium magnetic-element">Integrations</a>
                    <a href="#pricing" class="nav-link text-gray-600 hover:text-gray-900 font-medium magnetic-element">Pricing</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('register') }}" class="hidden sm:block text-gray-600 hover:text-gray-900 font-medium transition-all duration-300 magnetic-element">Sign in</a>
                    <a href="{{ route('login') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium magnetic-element">Get Started</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-600 hover:text-gray-900 magnetic-element" id="mobile-menu-btn">
                        <svg class="h-6 w-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden glass-effect" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                <a href="#features" class="block px-3 py-2 text-gray-600 hover:text-gray-900 transition-all duration-300">Features</a>
                <a href="#solutions" class="block px-3 py-2 text-gray-600 hover:text-gray-900 transition-all duration-300">Solutions</a>
                <a href="#integrations" class="block px-3 py-2 text-gray-600 hover:text-gray-900 transition-all duration-300">Integrations</a>
                <a href="#pricing" class="block px-3 py-2 text-gray-600 hover:text-gray-900 transition-all duration-300">Pricing</a>
                <a href="#" class="block px-3 py-2 text-gray-600 hover:text-gray-900 transition-all duration-300">Sign in</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient relative overflow-hidden min-h-screen flex items-center">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-10 left-10 w-20 h-20 bg-note-yellow rounded-full opacity-20 animate-bounce-slow"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-note-purple rounded-full opacity-20 animate-float"></div>
            <div class="absolute bottom-20 left-32 w-24 h-24 bg-note-teal rounded-full opacity-20 animate-float-delayed"></div>
            <div class="absolute bottom-40 right-10 w-12 h-12 bg-note-pink rounded-full opacity-20 animate-float-delayed-2"></div>
        </div>

        <!-- Floating Elements -->
        <div class="floating-element animate-float top-20 left-10 w-48 h-32 bg-note-yellow p-4 transform rotate-3 reveal-animation" style="--stagger: 1">
            <div class="text-sm text-gray-800 font-medium">Take notes on</div>
            <div class="text-sm text-gray-800">ideas or create checklists</div>
            <div class="text-sm text-gray-800">and accomplish more</div>
            <div class="text-sm text-gray-800 font-medium">tasks with ease.</div>
            <div class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
        </div>

        <div class="floating-element animate-float-delayed top-32 right-16 w-40 h-28 glass-effect p-3 reveal-animation" style="--stagger: 2">
            <div class="flex items-center space-x-2 mb-2">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center glow-effect">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-800">Reminders</span>
            </div>
            <div class="text-xs text-gray-600">Today's Meeting</div>
            <div class="text-xs text-gray-500 mt-1">📅 10:00 AM</div>
        </div>

        <div class="floating-element animate-float-delayed-2 bottom-32 left-16 w-44 h-36 glass-effect p-3 reveal-animation" style="--stagger: 3">
            <div class="text-xs font-medium text-gray-800 mb-2">Today's tasks</div>
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-sm animate-pulse"></div>
                    <span class="text-xs text-gray-700">New ideas for campaign</span>
                    <div class="w-5 h-5 bg-gray-200 rounded-full"></div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-sm animate-pulse"></div>
                    <span class="text-xs text-gray-700">Design PPT #3</span>
                    <div class="w-5 h-5 bg-gray-200 rounded-full"></div>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">75% complete</div>
            <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                <div class="bg-blue-500 h-1 rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
            </div>
        </div>

        <div class="floating-element animate-float bottom-20 right-20 w-36 h-32 glass-effect p-3 reveal-animation" style="--stagger: 4">
            <div class="text-xs font-medium text-gray-800 mb-2">100+ integrations</div>
            <div class="grid grid-cols-3 gap-2">
                <div class="w-8 h-8 bg-red-500 rounded flex items-center justify-center text-white text-xs font-bold glow-effect">G</div>
                <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center text-white text-xs font-bold glow-effect">S</div>
                <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center text-white text-xs font-bold glow-effect">T</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal-animation" style="--stagger: 0">
                <h1 class="text-5xl md:text-7xl font-google-sans font-bold text-gray-900 mb-6">
                    <span class="typewriter">Think, plan, and</span>
                    <span class="block gradient-text">organize</span>
                    <span class="block text-gray-400">all in one place</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto reveal-animation" style="--stagger: 1">
                    Efficiently capture your thoughts, manage your tasks and boost productivity with MyNotes.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center reveal-animation" style="--stagger: 2">
                    <button class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg magnetic-element">
                        Get free demo
                    </button>
                    <button class="btn-secondary text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg magnetic-element">
                        Watch video
                    </button>
                </div>
            </div>
        </div>

        <!-- Decorative animated dots -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-0 rotating-element">
            <div class="grid grid-cols-4 gap-4 opacity-20">
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="w-3 h-3 bg-gray-800 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 1.5s;"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal-animation">
                <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4 gradient-text">
                    Everything you need to stay organized
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From simple notes to complex projects, MyNotes adapts to your workflow and helps you achieve more.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200 reveal-animation stagger-animation" style="--stagger: 1">
                    <div class="w-12 h-12 bg-note-yellow rounded-lg flex items-center justify-center mb-6 morphing-shape glow-effect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 wiggle-element" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-google-sans font-semibold text-gray-900 mb-3">Quick Notes</h3>
                    <p class="text-gray-600">Capture ideas instantly with our intuitive note-taking interface. Never lose a thought again.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200 reveal-animation stagger-animation" style="--stagger: 2">
                    <div class="w-12 h-12 bg-note-purple rounded-lg flex items-center justify-center mb-6 morphing-shape glow-effect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 wiggle-element" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-google-sans font-semibold text-gray-900 mb-3">Smart Labels</h3>
                    <p class="text-gray-600">Organize your notes with intelligent labeling system that learns from your habits.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card p-8 rounded-2xl border border-gray-200 reveal-animation stagger-animation" style="--stagger: 3">
                    <div class="w-12 h-12 bg-note-teal rounded-lg flex items-center justify-center mb-6 morphing-shape glow-effect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 wiggle-element" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    <section class="py-20 bg-gray-50 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-gradient-to-br from-yellow-200 to-orange-200 rounded-full opacity-30 animate-float"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full opacity-30 animate-float-delayed"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal-animation">
                <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4 gradient-text">
                    See MyNotes in action
                </h2>
                <p class="text-xl text-gray-600">
                    Experience the power of organized thinking
                </p>
            </div>

            <!-- Demo Notes Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                <!-- Note 1 -->
                <div class="note-card bg-note-teal p-4 rounded-lg shadow-sm reveal-animation stagger-animation magnetic-element" style="--stagger: 1">
                    <h3 class="font-semibold text-gray-800 mb-2">Project Ideas</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        • Website redesign<br>
                        • Mobile app concept<br>
                        • User research
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>2h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs animate-pulse">work</span>
                        </div>
                    </div>
                </div>

                <!-- Note 2 -->
                <div class="note-card bg-note-purple p-4 rounded-lg shadow-sm reveal-animation stagger-animation magnetic-element" style="--stagger: 2">
                    <h3 class="font-semibold text-gray-800 mb-2">Life Goals</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Learn new skills, travel more, and maintain work-life balance.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>1d ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs animate-pulse">personal</span>
                        </div>
                    </div>
                </div>

                <!-- Note 3 -->
                <div class="note-card bg-white p-4 rounded-lg shadow-sm border border-gray-200 reveal-animation stagger-animation magnetic-element" style="--stagger: 3">
                    <h3 class="font-semibold text-gray-800 mb-2">Meeting Notes</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Discussed Q4 goals and upcoming product launch timeline.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>3h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs animate-pulse">important</span>
                        </div>
                    </div>
                </div>

                <!-- Note 4 -->
                <div class="note-card bg-note-yellow p-4 rounded-lg shadow-sm reveal-animation stagger-animation magnetic-element" style="--stagger: 4">
                    <h3 class="font-semibold text-gray-800 mb-2">Shopping List</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        • Groceries<br>
                        • Office supplies<br>
                        • Birthday gift
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>5h ago</span>
                        <div class="flex space-x-1">
                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs animate-pulse">personal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integrations Section -->
    <section id="integrations" class="py-20 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal-animation">
                <h2 class="text-4xl font-google-sans font-bold text-gray-900 mb-4 gradient-text">
                    Integrates with your favorite tools
                </h2>
                <p class="text-xl text-gray-600 mb-12">
                    Connect MyNotes with 100+ apps and services you already use
                </p>
            </div>

            <div class="flex flex-wrap justify-center items-center gap-8 reveal-animation" style="--stagger: 1">
                <!-- Integration Icons -->
                <div class="integration-icon w-16 h-16 bg-red-500 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">G</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-purple-600 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">S</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">T</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">E</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-orange-500 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">N</span>
                </div>
                <div class="integration-icon w-16 h-16 bg-pink-500 rounded-lg flex items-center justify-center magnetic-element">
                    <span class="text-white font-bold text-xl">F</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-float"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-white opacity-10 rounded-full animate-float-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-white opacity-5 rounded-full animate-float-delayed-2"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal-animation">
                <h2 class="text-4xl font-google-sans font-bold text-white mb-4">
                    Ready to get organized?
                </h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Join thousands of users who have transformed their productivity with MyNotes
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all duration-300 magnetic-element glow-effect">
                        Start free trial
                    </button>
                    <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-indigo-600 transition-all duration-300 magnetic-element">
                        Schedule demo
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="reveal-animation">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center glow-effect">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-xl font-google-sans font-semibold gradient-text">MyNotes</span>
                    </div>
                    <p class="text-gray-400">
                        The ultimate note-taking and organization platform for modern professionals.
                    </p>
                </div>
                
                <div class="reveal-animation stagger-animation" style="--stagger: 1">
                    <h3 class="font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">API</a></li>
                    </ul>
                </div>
                
                <div class="reveal-animation stagger-animation" style="--stagger: 2">
                    <h3 class="font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Contact</a></li>
                    </ul>
                </div>
                
                <div class="reveal-animation stagger-animation" style="--stagger: 3">
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Community</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Privacy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors magnetic-element">Terms</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 reveal-animation">
                <p>&copy; 2024 MyNotes. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize GSAP and ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Loading screen
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('hidden');
                initializeAnimations();
            }, 2000);
        });

        function initializeAnimations() {
            // Scroll progress indicator
            gsap.to("#scrollIndicator", {
                scaleX: 1,
                ease: "none",
                scrollTrigger: {
                    trigger: "body",
                    start: "top top",
                    end: "bottom bottom",
                    scrub: true
                }
            });

            // Reveal animations on scroll
            gsap.utils.toArray('.reveal-animation').forEach((element, index) => {
                const stagger = element.style.getPropertyValue('--stagger') || 0;
                
                gsap.fromTo(element, 
                    {
                        opacity: 0,
                        y: 50,
                        scale: 0.9
                    },
                    {
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        duration: 0.8,
                        delay: stagger * 0.1,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: element,
                            start: "top 80%",
                            end: "bottom 20%",
                            toggleActions: "play none none reverse"
                        }
                    }
                );
            });

            // Parallax effects
            gsap.utils.toArray('.parallax-element').forEach(element => {
                gsap.to(element, {
                    yPercent: -50,
                    ease: "none",
                    scrollTrigger: {
                        trigger: element,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true
                    }
                });
            });

            // Floating elements enhanced animation
            gsap.utils.toArray('.floating-element').forEach((element, index) => {
                gsap.to(element, {
                    y: -30,
                    rotation: 5,
                    duration: 3 + index,
                    ease: "power2.inOut",
                    repeat: -1,
                    yoyo: true,
                    delay: index * 0.5
                });
            });

            // Note cards stagger animation
            gsap.fromTo('.note-card', 
                {
                    opacity: 0,
                    y: 100,
                    rotationY: -15
                },
                {
                    opacity: 1,
                    y: 0,
                    rotationY: 0,
                    duration: 0.8,
                    stagger: 0.2,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: '.note-card',
                        start: "top 80%"
                    }
                }
            );

            // Integration icons animation
            gsap.fromTo('.integration-icon', 
                {
                    scale: 0,
                    rotation: 180
                },
                {
                    scale: 1,
                    rotation: 0,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: '.integration-icon',
                        start: "top 80%"
                    }
                }
            );

            // Feature cards 3D animation
            gsap.utils.toArray('.feature-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        rotationY: 10,
                        rotationX: 5,
                        z: 50,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });

                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        rotationY: 0,
                        rotationX: 0,
                        z: 0,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });
            });
        }

        // Cursor trail effect
        const cursorTrail = document.getElementById('cursorTrail');
        let mouseX = 0, mouseY = 0;
        let trailX = 0, trailY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animateTrail() {
            trailX += (mouseX - trailX) * 0.1;
            trailY += (mouseY - trailY) * 0.1;
            
            cursorTrail.style.left = trailX + 'px';
            cursorTrail.style.top = trailY + 'px';
            
            requestAnimationFrame(animateTrail);
        }
        animateTrail();

        // Magnetic effect for elements
        document.querySelectorAll('.magnetic-element').forEach(element => {
            element.addEventListener('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                gsap.to(element, {
                    x: x * 0.3,
                    y: y * 0.3,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            element.addEventListener('mouseleave', () => {
                gsap.to(element, {
                    x: 0,
                    y: 0,
                    duration: 0.5,
                    ease: "elastic.out(1, 0.3)"
                });
            });
        });

        // Mobile menu toggle with animation
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const icon = this.querySelector('svg');
            
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                gsap.fromTo(mobileMenu, 
                    { opacity: 0, y: -20 },
                    { opacity: 1, y: 0, duration: 0.3 }
                );
                gsap.to(icon, { rotation: 90, duration: 0.3 });
            } else {
                gsap.to(mobileMenu, {
                    opacity: 0,
                    y: -20,
                    duration: 0.3,
                    onComplete: () => {
                        mobileMenu.classList.add('hidden');
                    }
                });
                gsap.to(icon, { rotation: 0, duration: 0.3 });
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: target,
                        ease: "power2.inOut"
                    });
                }
            });
        });

        // Navbar background change on scroll
        ScrollTrigger.create({
            start: "top -80",
            end: 99999,
            toggleClass: {className: "glass-effect", targets: "#navbar"}
        });

        // Particles.js initialization
        if (typeof particlesJS !== 'undefined') {
            particlesJS('particles-js', {
                particles: {
                    number: { value: 50 },
                    color: { value: "#4f46e5" },
                    shape: { type: "circle" },
                    opacity: { value: 0.1 },
                    size: { value: 3 },
                    move: {
                        enable: true,
                        speed: 1,
                        direction: "none",
                        random: true,
                        out_mode: "out"
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" }
                    }
                }
            });
        }

        // Button click animations
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple-effect');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple effect styles
        const style = document.createElement('style');
        style.textContent = `
            .ripple-effect {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Typewriter effect for hero title
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

        // Initialize typewriter effect
        setTimeout(() => {
            const typewriterElement = document.querySelector('.typewriter');
            if (typewriterElement) {
                typeWriter(typewriterElement, 'Think, plan, and', 100);
            }
        }, 3000);

        // Performance optimization: Reduce animations on low-end devices
        if (navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-duration', '0.1s');
        }

        // Add intersection observer for better performance
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.reveal-animation').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>