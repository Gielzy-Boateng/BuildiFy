{{-- resources/views/public/page.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $page->content['about_text'] ?? '' }}">
    <title>{{ $page->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: {{ $page->theme->primary_color }};
            --secondary-color: {{ $page->theme->secondary_color }};
            --background-color: {{ $page->theme->background_color }};
            --text-color: {{ $page->theme->text_color }};
            --accent-color: {{ $page->theme->accent_color }};
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body style="background-color: var(--background-color); color: var(--text-color);" x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 50">
    
    {{-- Modern Navigation Bar --}}
    <nav class="fixed w-full z-50 transition-all duration-300" 
         :class="scrolled ? 'bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-md' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                {{-- Logo --}}
                <div class="flex-shrink-0 rounded-full">
                    @if($page->logo)
                        <img src="{{ asset($page->logo) }}" alt="Logo" class="h-12 w-12 rounded-full object-cover">
                    @else
                        <span class="text-2xl font-extrabold tracking-tight" :style="scrolled ? 'color: #111827' : 'color: white'">{{ $page->user->name }}</span>
                    @endif
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#home" class="px-3 py-2 rounded-md text-sm font-semibold transition-colors" 
                       :style="scrolled ? 'color: #111827' : 'color: white'">
                        Home
                    </a>
                    <a href="#about" class="px-3 py-2 rounded-md text-sm font-semibold transition-colors" 
                       :style="scrolled ? 'color: #111827' : 'color: white'">
                        About
                    </a>
                    <a href="#services" class="px-3 py-2 rounded-md text-sm font-semibold transition-colors" 
                       :style="scrolled ? 'color: #111827' : 'color: white'">
                        Services
                    </a>
                    <a href="#contact" class="ml-2 px-5 py-2.5 rounded-full text-sm font-semibold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5" 
                       style="background-color: var(--primary-color); color: white;">
                        Contact
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg transition-colors" :style="scrolled ? 'color: #111827' : 'color: white'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="md:hidden bg-white/90 backdrop-blur-md border-t border-gray-100 shadow-xl">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#home" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg font-semibold" :style="'color: #111827'">Home</a>
                <a href="#about" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg font-semibold" :style="'color: #111827'">About</a>
                <a href="#services" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg font-semibold" :style="'color: #111827'">Services</a>
                <a href="#contact" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg text-white font-semibold text-center" style="background-color: var(--primary-color);">Contact</a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <header id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden" 
            style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
        @if($page->hero_image)
        <div class="absolute inset-0 opacity-20">
            <img src="{{ asset($page->hero_image) }}" alt="Hero Background" class="w-full h-full object-cover">
        </div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-transparent"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center fade-in-up">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                {{ $page->content['hero_heading'] ?? 'Welcome' }}
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8 leading-relaxed">
                {{ $page->content['hero_subheading'] ?? '' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#about" class="px-8 py-4 bg-white text-gray-900 rounded-full font-bold shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300">
                    Learn More
                </a>
                <a href="#contact" class="px-8 py-4 border-2 border-white text-white rounded-full font-bold hover:bg-white hover:text-gray-900 transition-all duration-300">
                    Get Started
                </a>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
            <a href="#about" class="animate-bounce block">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </a>
        </div>
    </header>

    {{-- About Section --}}
    <section id="about" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-6" style="color: var(--primary-color);">
                    {{ $page->content['about_heading'] ?? 'About Us' }}
                </h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded-full" style="background-color: var(--accent-color);"></div>
            </div>
            <div class="max-w-4xl mx-auto">
                <p class="text-xl md:text-2xl leading-relaxed text-gray-700 text-center fade-in-up">
                    {{ $page->content['about_text'] ?? '' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="services" class="py-24" style="background: linear-gradient(to bottom, #f9fafb, #ffffff);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-6" style="color: var(--primary-color);">
                    {{ $page->content['services_heading'] ?? 'Our Services' }}
                </h2>
                <div class="w-24 h-1 mx-auto mb-8 rounded-full" style="background-color: var(--accent-color);"></div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Discover what we can do for you
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for($i = 1; $i <= 3; $i++)
                <div class="bg-white p-8 rounded-2xl shadow-xl hover-lift fade-in-up border border-gray-100" style="animation-delay: {{ $i * 0.1 }}s;">
                    <div class="w-16 h-16 rounded-full mb-6 flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4" style="color: var(--secondary-color);">
                        {{ $page->content["service_{$i}_title"] ?? "Service {$i}" }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $page->content["service_{$i}_description"] ?? '' }}
                    </p>
                </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact" class="py-24 relative overflow-hidden">
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto rounded-3xl bg-white border border-gray-100 shadow-xl p-8 md:p-12 text-center">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4" style="color: var(--primary-color);">
                    {{ $page->content['contact_heading'] ?? 'Contact Us' }}
                </h2>
                <p class="text-lg md:text-xl mb-10" style="color: var(--text-color);">
                    {{ $page->content['contact_text'] ?? '' }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="mailto:contact@desmondboateng483@gmail.com" class="group rounded-2xl px-6 py-5 shadow-lg transition-transform duration-200 hover:-translate-y-0.5" style="background-color: var(--primary-color); color: white;">
                        <div class="flex items-center justify-center">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-sm font-semibold tracking-wide">Email Us</span>
                        </div>
                    </a>
                    <a href="tel:+233571991014" class="group rounded-2xl px-6 py-5 shadow-lg border-2 transition-transform duration-200 hover:-translate-y-0.5" style="border-color: var(--primary-color); color: var(--primary-color);">
                        <div class="flex items-center justify-center">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full mr-3" style="background-color: var(--primary-color); color: white;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <span class="text-sm font-semibold tracking-wide">Call Us</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="text-white py-12" style="background-color: var(--primary-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                {{-- Footer Column 1 --}}
<div>
    @if($page->logo)
        <img src="{{ asset($page->logo) }}" alt="Logo" class="h-12 w-12 rounded-full object-cover mb-4">
    @else
        <h3 class="text-2xl font-bold mb-4">{{ $page->user->name }}</h3>
    @endif
    <p class="text-white/80">
        {{ Str::limit($page->content['about_text'] ?? '', 100) }}
    </p>
</div>

                {{-- Footer Column 2 --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-white/80 hover:text-white hover:underline underline-offset-4 decoration-white/60 transition">Home</a></li>
                        <li><a href="#about" class="text-white/80 hover:text-white hover:underline underline-offset-4 decoration-white/60 transition">About</a></li>
                        <li><a href="#services" class="text-white/80 hover:text-white hover:underline underline-offset-4 decoration-white/60 transition">Services</a></li>
                        <li><a href="#contact" class="text-white/80 hover:text-white hover:underline underline-offset-4 decoration-white/60 transition">Contact</a></li>
                    </ul>
                </div>

                {{-- Footer Column 3 --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/25 pt-8 text-center">
                <p class="text-white" style="opacity:0.95;">
                    &copy; {{ date('Y') }} {{ $page->user->name }}. All rights reserved.
                </p>
                <p class="text-white text-sm mt-2" style="opacity:0.9;">
                    Digitally Created and Crafted by <span class="font-semibold" style="opacity:0.95;">Desmond Boateng</span>
                </p>
            </div>
        </div>
    </footer>

    {{-- Scroll to Top Button --}}
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
            x-show="scrolled"
            x-transition
            class="fixed bottom-8 right-8 w-12 h-12 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform z-40"
            style="background-color: var(--primary-color);">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</body>
</html>