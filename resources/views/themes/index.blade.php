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
        <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $theme->name }}</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 w-24">Primary:</span>
                        <div class="w-16 h-8 rounded border" style="background-color: {{ $theme->primary_color }}"></div>
                        <span class="ml-2 text-xs text-gray-500">{{ $theme->secondary_color }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 w-24">Background:</span>
                        <div class="w-16 h-8 rounded border" style="background-color: {{ $theme->background_color }}"></div>
                        <span class="ml-2 text-xs text-gray-500">{{ $theme->background_color }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 w-24">Text:</span>
                        <div class="w-16 h-8 rounded border" style="background-color: {{ $theme->text_color }}"></div>
                        <span class="ml-2 text-xs text-gray-500">{{ $theme->text_color }}</span>
                    </div>
                </div>

                @if(auth()->user()->page && auth()->user()->page->theme_id === $theme->id)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Currently Active
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection