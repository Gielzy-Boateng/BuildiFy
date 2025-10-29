<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Buildify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
                 class="flex items-start p-4 rounded-xl shadow-lg border backdrop-blur max-w-md">
                
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
                </div>
                
                <div class="flex-1 mr-2">
                    <p :class="{
                        'text-green-800': toast.type === 'success',
                        'text-red-800': toast.type === 'error',
                        'text-blue-800': toast.type === 'info',
                        'text-yellow-800': toast.type === 'warning'
                    }" class="text-sm font-medium" x-text="toast.message"></p>
                </div>
                
                <button @click="removeToast(toast.id)" class="flex-shrink-0 ml-2">
                    <svg :class="{
                        'text-green-400 hover:text-green-600': toast.type === 'success',
                        'text-red-400 hover:text-red-600': toast.type === 'error'
                    }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 md:p-10 border border-white/20">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                        Welcome Back
                    </h2>
                    <p class="text-sm text-gray-600">
                        Sign in to your <span class="font-semibold text-indigo-600">Buildify</span> account
                    </p>
                </div>
                
                <form class="space-y-5" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
                            <input id="email" name="email" type="email" required 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" 
                                placeholder="you@example.com" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="password" required 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 font-medium">
                            Remember me
                        </label>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transition">
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
                            Create one now
                        </a>
                    </p>
                </div>
            </div>
        </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                @foreach($errors->all() as $error)
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'error', message: "{{ $error }}", timeout: 5000 }
                    }));
                @endforeach
            @endif

            @if(session('success'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'success', message: "{{ session('success') }}", timeout: 3000 }
                }));
            @endif

            @if(session('error'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', message: "{{ session('error') }}", timeout: 5000 }
                }));
            @endif
        });
    </script>
</body>
</html>
