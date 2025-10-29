{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buildify Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
    @auth
    <nav class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent tracking-tight">
                            Buildify Dashboard
                        </a>
                    </div>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-2 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('pages.edit', auth()->user()->page) }}" class="inline-flex items-center px-2 pt-1 border-b-2 {{ request()->routeIs('pages.*') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            Edit Page
                        </a>
                        <a href="{{ route('analytics.index') }}" class="inline-flex items-center px-2 pt-1 border-b-2 {{ request()->routeIs('analytics.*') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            Analytics
                        </a>
                        <a href="{{ route('themes.index') }}" class="inline-flex items-center px-2 pt-1 border-b-2 {{ request()->routeIs('themes.*') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            Themes
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="hidden sm:inline text-gray-700 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="flex items-start p-4 rounded-xl border border-green-200 bg-green-50/80 shadow-sm">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="flex items-start p-4 rounded-xl border border-red-200 bg-red-50/80 shadow-sm">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <main class="py-10 animate-[fadeIn_300ms_ease-out]">
        @yield('content')
    </main>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @stack('scripts')

    {{-- Toast Notifications --}}
<div x-data="toastManager()" 
     @notify.window="addToast($event.detail)"
     class="fixed top-4 right-4 z-50 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :class="{
                 'bg-green-50/90 border-green-200': toast.type === 'success',
                 'bg-red-50/90 border-red-200': toast.type === 'error',
                 'bg-blue-50/90 border-blue-200': toast.type === 'info',
                 'bg-yellow-50/90 border-yellow-200': toast.type === 'warning'
             }"
             class="flex items-start p-4 rounded-xl shadow-lg border max-w-md backdrop-blur">
            
            {{-- Icon --}}
            <div class="flex-shrink-0 mr-3">
                <template x-if="toast.type === 'success'">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </template>
            </div>
            
            {{-- Message --}}
            <div class="flex-1 mr-2">
                <p :class="{
                    'text-green-800': toast.type === 'success',
                    'text-red-800': toast.type === 'error',
                    'text-blue-800': toast.type === 'info',
                    'text-yellow-800': toast.type === 'warning'
                }" class="text-sm font-medium" x-text="toast.message"></p>
            </div>
            
            {{-- Close Button --}}
            <button @click="removeToast(toast.id)" class="flex-shrink-0 ml-2">
                <svg :class="{
                    'text-green-400 hover:text-green-600': toast.type === 'success',
                    'text-red-400 hover:text-red-600': toast.type === 'error',
                    'text-blue-400 hover:text-blue-600': toast.type === 'info',
                    'text-yellow-400 hover:text-yellow-600': toast.type === 'warning'
                }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
function toastManager() {
    return {
        toasts: [],
        nextId: 1,
        
        addToast(detail) {
            const id = this.nextId++;
            const toast = {
                id,
                type: detail.type || 'info',
                message: detail.message || 'Notification',
                visible: true
            };
            
            this.toasts.push(toast);
            
            // Auto remove after timeout
            const timeout = detail.timeout || 5000;
            setTimeout(() => {
                this.removeToast(id);
            }, timeout);
        },
        
        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 300);
            }
        }
    }
}
</script>

</body>
</html>