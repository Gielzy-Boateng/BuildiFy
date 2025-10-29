{{-- resources/views/themes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Themes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Available Themes</h1>
        <p class="mt-2 text-gray-600">Choose a theme for your page</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $theme)
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5">
            <div class="h-28" style="background: linear-gradient(135deg, {{ $theme->primary_color }}, {{ $theme->secondary_color }});"></div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $theme->name }}</h3>
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="rounded-lg h-10 border" style="background-color: {{ $theme->primary_color }}"></div>
                    <div class="rounded-lg h-10 border" style="background-color: {{ $theme->secondary_color }}"></div>
                    <div class="rounded-lg h-10 border" style="background-color: {{ $theme->accent_color }}"></div>
                </div>
                <div class="rounded-xl p-4 border text-sm" style="background-color: {{ $theme->background_color }}; color: {{ $theme->text_color }};">
                    Sample text on background to ensure readability
                </div>
                @if(auth()->user()->page && auth()->user()->page->theme_id === $theme->id)
                <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Currently Active</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection