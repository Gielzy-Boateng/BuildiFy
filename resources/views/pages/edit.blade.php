{{-- resources/views/pages/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Page</h1>
            <p class="mt-2 text-gray-600">Customize your page content and design</p>
        </div>
        <div class="flex space-x-3">
            @if($page->is_published)
            <form method="POST" action="{{ route('pages.unpublish', $page) }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>
                    Unpublish
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('pages.publish', $page) }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Publish Page
                </button>
            </form>
            @endif
        </div>
    </div>

    <div x-data="pageEditor()" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Editor Panel --}}
        <div class="space-y-6">
            {{-- Theme Selection --}}
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    Select Theme
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($themes as $theme)
                    <div @click="selectTheme({{ $theme->id }}, '{{ $theme->primary_color }}', '{{ $theme->secondary_color }}', '{{ $theme->background_color }}', '{{ $theme->text_color }}')" 
                         :class="selectedTheme === {{ $theme->id }} ? 'ring-2 ring-indigo-500 bg-indigo-50' : ''"
                         class="cursor-pointer border-2 rounded-xl p-4 hover:shadow-lg hover:scale-105 transition-all duration-200">
                        <p class="font-semibold text-gray-900 mb-2">{{ $theme->name }}</p>
                        <div class="flex space-x-2">
                            <div class="w-8 h-8 rounded-full shadow-inner" style="background-color: {{ $theme->primary_color }}"></div>
                            <div class="w-8 h-8 rounded-full shadow-inner" style="background-color: {{ $theme->secondary_color }}"></div>
                            <div class="w-8 h-8 rounded-full border-2 shadow-inner" style="background-color: {{ $theme->background_color }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Content Editor --}}
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Content
                </h3>
                <form @submit.prevent="saveContent" class="space-y-6">
                    <!-- Hero Section -->
                    <div class="space-y-5 bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Hero Section
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hero Heading</label>
                                <input type="text" x-model="content.hero_heading" 
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hero Subheading</label>
                                <input type="text" x-model="content.hero_subheading" 
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- About Section -->
                    <div class="space-y-5 bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            About Section
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">About Heading</label>
                                <input type="text" x-model="content.about_heading" 
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">About Text</label>
                                <textarea x-model="content.about_text" rows="3"
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="space-y-5">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Services Section
                        </h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Services Heading</label>
                            <input type="text" x-model="content.services_heading" 
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                        </div>

                        <!-- Individual Services -->
                    <div class="space-y-4">
                        <h5 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                            </svg>
                            Service Items
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="i in 3" :key="i">
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">
                                            Service <span x-text="i"></span>
                                        </span>
                                        <span class="text-xs text-gray-500">#{i}</span>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                            <input type="text" x-model="content['service_' + i + '_title']" 
                                                class="block w-full px-3 py-2 text-sm rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-100 focus:border-indigo-300 transition duration-150">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                            <textarea x-model="content['service_' + i + '_description']" rows="2"
                                                class="block w-full px-3 py-2 text-sm rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-100 focus:border-indigo-300 transition duration-150 resize-none"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="space-y-5 bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Contact Section
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Heading</label>
                                <input type="text" x-model="content.contact_heading" 
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Text</label>
                                <textarea x-model="content.contact_text" rows="2"
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Change Description -->
                    <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-indigo-800 mb-1.5 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Change Description (Optional)
                            </label>
                            <input type="text" x-model="changeDescription" 
                                placeholder="e.g., Updated hero section with new messaging"
                                class="block w-full px-4 py-2.5 rounded-lg border border-indigo-100 bg-white text-gray-800 placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition duration-200 ease-in-out shadow-sm">
                            <p class="text-xs text-indigo-600 mt-1">Add a brief description of the changes you're making</p>
                        </div>
                    </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" :disabled="saving"
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-lg shadow-lg text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <svg x-show="!saving" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg x-show="saving" class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Image Upload --}}
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Images
                </h3>
                <div class="space-y-6">
                    {{-- Logo Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="relative">
                            <input type="file" 
                                   id="logo-input"
                                   @change="uploadImage($event, 'logo')" 
                                   accept="image/*"
                                   class="hidden">
                            
                            <button type="button"
                                    @click="$el.previousElementSibling.click()"
                                    :disabled="uploading.logo"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 mr-2 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-600 group-hover:text-indigo-600" x-text="uploading.logo ? 'Uploading...' : (hasLogo ? 'Change Logo' : 'Upload Logo')"></span>
                            </button>
                        </div>
                        
                        <div x-show="hasLogo" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mb-2">Current Logo:</p>
                            <img :src="logoUrl" alt="Logo Preview" class="h-20 object-contain mx-auto">
                        </div>
                    </div>

                    {{-- Hero Image Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image</label>
                        <div class="relative">
                            <input type="file" 
                                   id="hero-input"
                                   @change="uploadImage($event, 'hero')" 
                                   accept="image/*"
                                   class="hidden">
                            
                            <button type="button"
                                    @click="$el.previousElementSibling.click()"
                                    :disabled="uploading.hero"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 mr-2 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-600 group-hover:text-indigo-600" x-text="uploading.hero ? 'Uploading...' : (hasHero ? 'Change Hero Image' : 'Upload Hero Image')"></span>
                            </button>
                        </div>
                        
                        <div x-show="hasHero" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mb-2">Current Hero Image:</p>
                            <img :src="heroUrl" alt="Hero Preview" class="h-32 w-full object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Preview Panel --}}
        <div class="lg:sticky lg:top-4 h-fit">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-gray-600 to-gray-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Live Preview
                    </h3>
                </div>
                <div class="border-t-4 border-indigo-500"></div>
                <div class="overflow-y-auto" style="height: 700px;">
                    <div :style="'background-color: ' + themeColors.background + '; color: ' + themeColors.text">
                        {{-- Hero Section Preview --}}
                        {{-- <div class="relative p-12 text-center" :style="'background: linear-gradient(135deg, ' + themeColors.primary + ', ' + themeColors.secondary + ')'">
                            <div x-show="hasLogo" class="mb-6">
                                <img :src="logoUrl" alt="Logo" class="h-16 mx-auto">
                         </div>
                            <h1 class="text-4xl font-bold mb-4 text-white" x-text="content.hero_heading"></h1>
                            <p class="text-xl text-white opacity-90" x-text="content.hero_subheading"></p>
                        </div> --}}

                        {{-- Hero Section Preview --}}
<div class="relative overflow-hidden min-h-[400px]">
    {{-- Background: Either Hero Image or Gradient --}}
    <div class="absolute inset-0">
        <template x-if="hasHero">
            <div class="relative w-full h-full">
                <img :src="heroUrl" alt="Hero" class="w-full h-full object-cover">
                <div class="absolute inset-0" 
                     :style="'background: linear-gradient(135deg, ' + themeColors.primary + '99, ' + themeColors.secondary + '99)'"></div>
            </div>
        </template>
        <template x-if="!hasHero">
            <div class="w-full h-full" 
                 :style="'background: linear-gradient(135deg, ' + themeColors.primary + ', ' + themeColors.secondary + ')'"></div>
        </template>
    </div>
    
    {{-- Hero Content (Overlay) --}}
    <div class="relative z-10 p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
        <div x-show="hasLogo" class="mb-6">
            <img :src="logoUrl" alt="Logo" class="h-16 mx-auto drop-shadow-2xl">
        </div>
        <h1 class="text-4xl font-bold mb-4 text-white drop-shadow-lg" x-text="content.hero_heading"></h1>
        <p class="text-xl text-white opacity-90 drop-shadow-md" x-text="content.hero_subheading"></p>
    </div>
</div>

                        {{-- About Section Preview --}}
                        <div class="p-10">
                            <h2 class="text-3xl font-bold mb-4" :style="'color: ' + themeColors.primary" x-text="content.about_heading"></h2>
                            <p class="text-lg leading-relaxed" x-text="content.about_text"></p>
                        </div>

                        {{-- Services Section Preview --}}
                        <div class="p-10 bg-gray-50">
                            <h2 class="text-3xl font-bold mb-8 text-center" :style="'color: ' + themeColors.primary" x-text="content.services_heading"></h2>
                            <div class="grid grid-cols-3 gap-4">
                                <template x-for="i in 3" :key="i">
                                    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
                                        <h3 class="font-semibold mb-2 text-sm" :style="'color: ' + themeColors.secondary" x-text="content['service_' + i + '_title']"></h3>
                                        <p class="text-xs text-gray-600" x-text="content['service_' + i + '_description']"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Contact Section Preview --}}
                        <div class="p-10 text-center">
                            <h2 class="text-3xl font-bold mb-4" :style="'color: ' + themeColors.primary" x-text="content.contact_heading"></h2>
                            <p class="text-lg" x-text="content.contact_text"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function pageEditor() {
    return {
        content: @json($page->content),
        selectedTheme: {{ $page->theme_id }},
        themeColors: {
            primary: '{{ $page->theme->primary_color }}',
            secondary: '{{ $page->theme->secondary_color }}',
            background: '{{ $page->theme->background_color }}',
            text: '{{ $page->theme->text_color }}'
        },
        changeDescription: '',
        saving: false,
        uploading: {
            logo: false,
            hero: false
        },
        logoUrl: '{{ $page->logo ?? "" }}',
        heroUrl: '{{ $page->hero_image ?? "" }}',
        hasLogo: {{ $page->logo ? 'true' : 'false' }},
        hasHero: {{ $page->hero_image ? 'true' : 'false' }},

        notify(type, message, timeout = 5000) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { type, message, timeout }
            }));
        },

        async selectTheme(id, primary, secondary, background, text) {
            const oldTheme = this.selectedTheme;
            this.selectedTheme = id;
            this.themeColors = { primary, secondary, background, text };
            
            try {
                const response = await fetch('{{ route("pages.update", $page) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ 
                        theme_id: id,
                        _method: 'PUT'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to update theme');
                }

                this.notify('success', 'Theme updated successfully!', 3000);
            } catch (error) {
                console.error('Theme update error:', error);
                // Revert theme on error
                this.selectedTheme = oldTheme;
                this.notify('error', error.message || 'Failed to update theme');
            }
        },

        async saveContent() {
            if (this.saving) return;
            
            this.saving = true;
            
            try {
                const response = await fetch('{{ route("pages.update", $page) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        content: this.content,
                        change_description: this.changeDescription,
                        _method: 'PUT'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to save changes');
                }

                this.notify('success', data.message || 'Changes saved successfully!', 3000);
                this.changeDescription = '';

            } catch (error) {
                console.error('Save error:', error);
                this.notify('error', error.message || 'Error saving changes. Please try again.');
            } finally {
                this.saving = false;
            }
        },

        async uploadImage(event, type) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file size
            if (file.size > 5 * 1024 * 1024) {
                this.notify('error', 'File size must be less than 5MB');
                event.target.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                this.notify('error', 'Please upload an image file');
                event.target.value = '';
                return;
            }

            this.uploading[type] = true;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', type);

            try {
                const response = await fetch('{{ route("pages.upload-image", $page) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Upload failed');
                }
                
                if (data.success) {
                    // Update preview immediately
                    if (type === 'logo') {
                        this.logoUrl = data.url;
                        this.hasLogo = true;
                    } else {
                        this.heroUrl = data.url;
                        this.hasHero = true;
                    }
                    
                    this.notify('success', `${type === 'logo' ? 'Logo' : 'Hero image'} uploaded successfully!`, 3000);
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            } catch (error) {
                console.error('Upload error:', error);
                this.notify('error', error.message || 'Error uploading image');
            } finally {
                this.uploading[type] = false;
                event.target.value = '';
            }
        }
    }
}
</script>
@endpush