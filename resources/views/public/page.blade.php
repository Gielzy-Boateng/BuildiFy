{{-- resources/views/public/page.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $page->content['about_text'] ?? '' }}">
    <title>{{ $page->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-color: {{ $page->theme->primary_color }};
            --secondary-color: {{ $page->theme->secondary_color }};
            --background-color: {{ $page->theme->background_color }};
            --text-color: {{ $page->theme->text_color }};
            --accent-color: {{ $page->theme->accent_color }};
        }
    </style>
</head>
<body style="background-color: var(--background-color); color: var(--text-color);">
    {{-- Hero Section --}}
    <header class="relative" style="background: linear-gradient(to right, var(--primary-color), var(--secondary-color));">
        @if($page->hero_image)
        <div class="absolute inset-0 opacity-30">
            <img src="{{ $page->hero_image }}" alt="Hero" class="w-full h-full object-cover">
        </div>
        @endif
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            @if($page->logo)
            <img src="{{ $page->logo }}" alt="Logo" class="h-16 mx-auto mb-8">
            @endif
            <h1 class="text-5xl font-bold text-white mb-6">
                {{ $page->content['hero_heading'] ?? 'Welcome' }}
            </h1>
            <p class="text-2xl text-white max-w-3xl mx-auto">
                {{ $page->content['hero_subheading'] ?? '' }}
            </p>
        </div>
    </header>

    {{-- About Section --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-8" style="color: var(--primary-color);">
                {{ $page->content['about_heading'] ?? 'About Us' }}
            </h2>
            <p class="text-xl leading-relaxed max-w-4xl">
                {{ $page->content['about_text'] ?? '' }}
            </p>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-12" style="color: var(--primary-color);">
                {{ $page->content['services_heading'] ?? 'Our Services' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for($i = 1; $i <= 3; $i++)
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <h3 class="text-2xl font-semibold mb-4" style="color: var(--secondary-color);">
                        {{ $page->content["service_{$i}_title"] ?? "Service {$i}" }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $page->content["service_{$i}_description"] ?? '' }}
                    </p>
                </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6" style="color: var(--primary-color);">
                {{ $page->content['contact_heading'] ?? 'Contact Us' }}
            </h2>
            <p class="text-xl max-w-2xl mx-auto">
                {{ $page->content['contact_text'] ?? '' }}
            </p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} {{ $page->user->name }}. All rights reserved.</p>
            <p class="mt-2 text-sm">Powered by CMS Platform</p>
        </div>
    </footer>
</body>
</html>