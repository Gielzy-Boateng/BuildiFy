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
                    Unpublish
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('pages.publish', $page) }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
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
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Select Theme</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($themes as $theme)
                    <div @click="selectTheme({{ $theme->id }}, '{{ $theme->primary_color }}', '{{ $theme->secondary_color }}', '{{ $theme->background_color }}', '{{ $theme->text_color }}')" 
                         :class="selectedTheme === {{ $theme->id }} ? 'ring-2 ring-indigo-500' : ''"
                         class="cursor-pointer border rounded-lg p-4 hover:shadow-md transition">
                        <p class="font-medium text-gray-900">{{ $theme->name }}</p>
                        <div class="mt-2 flex space-x-2">
                            <div class="w-6 h-6 rounded" style="background-color: {{ $theme->primary_color }}"></div>
                            <div class="w-6 h-6 rounded" style="background-color: {{ $theme->secondary_color }}"></div>
                            <div class="w-6 h-6 rounded border" style="background-color: {{ $theme->background_color }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Content Editor --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Content</h3>
                <form @submit.prevent="saveContent">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hero Heading</label>
                            <input type="text" x-model="content.hero_heading" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hero Subheading</label>
                            <input type="text" x-model="content.hero_subheading" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">About Heading</label>
                            <input type="text" x-model="content.about_heading" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">About Text</label>
                            <textarea x-model="content.about_text" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Services Heading</label>
                            <input type="text" x-model="content.services_heading" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Services --}}
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Services</h4>
                            <template x-for="i in 3" :key="i">
                                <div class="mb-4 p-3 bg-gray-50 rounded">
                                    <div class="mb-2">
                                        <label class="block text-xs font-medium text-gray-700" x-text="'Service ' + i + ' Title'"></label>
                                        <input type="text" x-model="content['service_' + i + '_title']" 
                                            class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700" x-text="'Service ' + i + ' Description'"></label>
                                        <textarea x-model="content['service_' + i + '_description']" rows="2"
                                            class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Heading</label>
                            <input type="text" x-model="content.contact_heading" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Text</label>
                            <textarea x-model="content.contact_text" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Change Description (Optional)</label>
                            <input type="text" x-model="changeDescription" 
                                placeholder="e.g., Updated hero section"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            <span x-show="!saving">Save Changes</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Image Upload --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <input type="file" @change="uploadImage($event, 'logo')" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if($page->logo)
                        <img src="{{ $page->logo }}" class="mt-2 h-20 object-contain">
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image</label>
                        <input type="file" @change="uploadImage($event, 'hero')" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if($page->hero_image)
                        <img src="{{ $page->hero_image }}" class="mt-2 h-32 object-cover rounded">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Preview Panel --}}
        <div class="lg:sticky lg:top-4 h-fit">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Live Preview</h3>
                <div class="border rounded-lg overflow-hidden" style="height: 600px; overflow-y: auto;">
                    <div :style="'background-color: ' + themeColors.background + '; color: ' + themeColors.text">
                        {{-- Hero Section --}}
                        <div class="p-8 text-center" :style="'background: linear-gradient(to right, ' + themeColors.primary + ', ' + themeColors.secondary + ')'">
                            <h1 class="text-4xl font-bold mb-4 text-white" x-text="content.hero_heading"></h1>
                            <p class="text-xl text-white" x-text="content.hero_subheading"></p>
                        </div>

                        {{-- About Section --}}
                        <div class="p-8">
                            <h2 class="text-3xl font-bold mb-4" :style="'color: ' + themeColors.primary" x-text="content.about_heading"></h2>
                            <p class="text-lg" x-text="content.about_text"></p>
                        </div>

                        {{-- Services Section --}}
                        <div class="p-8 bg-gray-50">
                            <h2 class="text-3xl font-bold mb-6 text-center" :style="'color: ' + themeColors.primary" x-text="content.services_heading"></h2>
                            <div class="grid grid-cols-3 gap-4">
                                <template x-for="i in 3" :key="i">
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <h3 class="font-semibold mb-2" :style="'color: ' + themeColors.secondary" x-text="content['service_' + i + '_title']"></h3>
                                        <p class="text-sm" x-text="content['service_' + i + '_description']"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Contact Section --}}
                        <div class="p-8 text-center">
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

        selectTheme(id, primary, secondary, background, text) {
            this.selectedTheme = id;
            this.themeColors = { primary, secondary, background, text };
            
            // Save theme selection
            fetch('{{ route("pages.update", $page) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme_id: id })
            });
        },

        async saveContent() {
            this.saving = true;
            
            try {
                const response = await fetch('{{ route("pages.update", $page) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        content: this.content,
                        change_description: this.changeDescription
                    })
                });

                if (response.ok) {
                    alert('Page updated successfully!');
                    this.changeDescription = '';
                }
            } catch (error) {
                alert('Error saving changes');
            } finally {
                this.saving = false;
            }
        },

        async uploadImage(event, type) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size
    if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB');
        event.target.value = '';
        return;
    }

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

        // Check if response is ok
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Upload failed');
        }

        const data = await response.json();
        
        if (data.success) {
            alert('Image uploaded successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Upload failed'));
        }
    } catch (error) {
        console.error('Upload error:', error);
        alert('Error uploading image: ' + error.message);
    } finally {
        event.target.value = '';
    }
}
    }
}
</script>
@endpush