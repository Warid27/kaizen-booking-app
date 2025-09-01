{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" x-data="{ 
    init() {
        // Initialize auth store (no demo/mock state)
        Alpine.store('auth', {
            isAuthed: false,
            role: 'user',
            userName: '',
            user: null,

            async login(credentials) {
                try {
                    const response = await window.authAPI.login(credentials);
                    if (response.token) {
                        this.isAuthed = true;
                        this.role = response.user.role || 'user';
                        this.userName = response.user.name;
                        this.user = response.user;
                        return { success: true, data: response };
                    }
                    return { success: false, error: 'Login failed' };
                } catch (error) {
                    return { success: false, error: window.handleApiError(error) };
                }
            },

            async register(userData) {
                try {
                    const response = await window.authAPI.register(userData);
                    if (response.token) {
                        this.isAuthed = true;
                        this.role = response.user.role || 'user';
                        this.userName = response.user.name;
                        this.user = response.user;
                        return { success: true, data: response };
                    }
                    return { success: false, error: 'Registration failed' };
                } catch (error) {
                    return { success: false, error: window.handleApiError(error) };
                }
            },

            async logout() {
                try {
                    await window.authAPI.logout();
                } catch (error) {
                    console.error('Logout error:', error);
                } finally {
                    this.isAuthed = false;
                    this.role = 'user';
                    this.userName = '';
                    this.user = null;
                    
                    localStorage.removeItem('authToken');

                    // Show success modal and redirect to /login on confirm
                    try {
                        const ui = Alpine.store('ui');
                        if (ui && typeof ui.showModal === 'function') {
                            ui.showModal(
                                'Logout Successful',
                                'You have been logged out successfully.',
                                () => { window.location.href = '/login'; },
                                null,
                                'OK',
                                ''
                            );
                        } else {
                            window.location.href = '/login';
                        }
                    } catch (e) {
                        window.location.href = '/login';
                    }
                }
            },

            async fetchUser() {
                try {
                    const response = await window.authAPI.getUser();
                    this.user = response;
                    this.userName = response.name;
                    this.role = response.role || 'user';
                } catch (error) {
                    console.error('Fetch user error:', error);
                    this.logout();
                }
            },

            init() {
                // Real auth init: rely on token existence only
                this.isAuthed = !!localStorage.getItem('authToken');
                if (this.isAuthed) {
                    this.fetchUser();
                }
            }
        });

        // Initialize UI store
        Alpine.store('ui', {
            loading: false,
            toasts: [],
            modal: {
                show: false,
                title: '',
                message: '',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                onConfirm: () => {},
                onCancel: () => {}
            },

            showLoading() {
                this.loading = true;
            },

            hideLoading() {
                this.loading = false;
            },

            addToast(type, message, duration = 3000) {
                const id = Date.now();
                const toast = { id, type, message, show: true };
                this.toasts.push(toast);
                
                setTimeout(() => {
                    this.removeToast(id);
                }, duration);
            },

            removeToast(id) {
                const index = this.toasts.findIndex(toast => toast.id === id);
                if (index > -1) {
                    this.toasts[index].show = false;
                    setTimeout(() => {
                        this.toasts.splice(index, 1);
                    }, 300);
                }
            },

            showModal(title, message, onConfirm, onCancel = null, confirmText = 'Confirm', cancelText = 'Cancel') {
                this.modal = {
                    show: true,
                    title,
                    message,
                    confirmText,
                    cancelText,
                    onConfirm: onConfirm || (() => {}),
                    onCancel: onCancel || (() => {})
                };
            },

            hideModal() {
                this.modal.show = false;
            }
        });
    }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KaiBook')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 min-h-screen" x-init="lucide.createIcons()">
    <!-- Loading Overlay -->
    <div 
        x-show="$store.ui.loading" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <i data-lucide="loader-2" class="h-6 w-6 text-blue-600 animate-spin"></i>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-200 sticky top-0 z-40" x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2 text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">
                        <img src="/favicon.svg" alt="KaiBook logo" class="h-6 w-6 object-contain" />
                        <span>KaiBook</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8" x-show="$store.auth.isAuthed">
                    <a 
                        href="/dashboard" 
                        class="inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md hover:bg-gray-100"
                        :class="window.location.pathname === '/dashboard' ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900'"
                    >
                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                        <span>Dashboard</span>
                    </a>
                    <a 
                        href="/rooms" 
                        class="inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md hover:bg-gray-100"
                        :class="window.location.pathname.startsWith('/rooms') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900'"
                    >
                        <i data-lucide="bed-double" class="h-4 w-4"></i>
                        <span>Rooms</span>
                    </a>
                    <a 
                        href="/bookings" 
                        class="inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md hover:bg-gray-100"
                        :class="window.location.pathname.startsWith('/bookings') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900'"
                    >
                        <i data-lucide="calendar-days" class="h-4 w-4"></i>
                        <span>Bookings</span>
                    </a>
                    <a 
                        href="/schedule" 
                        class="inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium transition-all duration-200 rounded-md hover:bg-gray-100"
                        :class="window.location.pathname === '/schedule' ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900'"
                    >
                        <i data-lucide="calendar-clock" class="h-4 w-4"></i>
                        <span>Schedule</span>
                    </a>
                </div>

                <!-- Desktop Right Side -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <template x-if="$store.auth.isAuthed">
                        <div class="relative" x-data="{ open: false }">
                            <!-- Profile dropdown button -->
                            <button 
                                @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center space-x-3 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                            >
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                    <i data-lucide="user" class="h-4 w-4 text-blue-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium" x-text="$store.auth.userName"></span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div 
                                x-show="open"
                                x-effect="open && lucide.createIcons()"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            >
                                <div class="py-1">
                                    <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i data-lucide="user" class="mr-3 h-4 w-4 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a href="/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i data-lucide="settings" class="mr-3 h-4 w-4 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <hr class="my-1">
                                    <button 
                                        @click="$store.auth.logout(); open = false" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors"
                                    >
                                        <svg class="mr-3 h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </button>
                            </div>
                        </div>
                    </template>
                    <template x-if="!$store.auth.isAuthed">
                        <div class="flex items-center space-x-4">
                            <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Login</a>
                            <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">Register</a>
                        </div>
                    </template>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors"
                    >
                        <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div 
                x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="md:hidden"
            >
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                    <template x-if="$store.auth.isAuthed">
                        <div>
                            <!-- Profile section -->
                            <div class="flex items-center px-3 py-3 border-b border-gray-200">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                    <i data-lucide="user" class="h-5 w-5 text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-gray-800" x-text="$store.auth.userName"></div>
                                    <div class="text-sm text-gray-500" x-text="$store.auth.user?.email || ''"></div>
                                </div>
                            </div>
                            
                            <!-- Navigation links -->
                            <a href="/dashboard" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors" :class="window.location.pathname === '/dashboard' ? 'bg-blue-50 text-blue-700' : ''">Dashboard</a>
                            <a href="/rooms" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors" :class="window.location.pathname.startsWith('/rooms') ? 'bg-blue-50 text-blue-700' : ''">Rooms</a>
                            <a href="/bookings" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors" :class="window.location.pathname.startsWith('/bookings') ? 'bg-blue-50 text-blue-700' : ''">Bookings</a>
                            <a href="/schedule" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors" :class="window.location.pathname === '/schedule' ? 'bg-blue-50 text-blue-700' : ''">Schedule</a>
                            
                            <!-- Profile actions -->
                            <div class="border-t border-gray-200 pt-4 pb-3">
                                <a href="#" class="flex items-center px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </a>
                                <a href="#" class="flex items-center px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </a>
                                <button 
                                    @click="$store.auth.logout(); mobileMenuOpen = false" 
                                    class="flex items-center w-full px-3 py-2 text-base font-medium text-red-700 hover:text-red-900 hover:bg-red-50 transition-colors"
                                >
                                    <svg class="mr-3 h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </template>
                    <template x-if="!$store.auth.isAuthed">
                        <div class="space-y-1">
                            <a href="/login" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors">Login</a>
                            <a href="/register" class="block px-3 py-2 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">Register</a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
                
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="fixed top-20 right-4 z-50 space-y-2">
        <template x-for="toast in $store.ui.toasts" :key="toast.id">
            <div 
                x-data="{ toast: toast }"
                x-show="toast.show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
                class="w-auto min-w-max shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden whitespace-nowrap"
                :class="{
                    'bg-white': toast.type === 'info',
                    'bg-green-50': toast.type === 'success',
                    'bg-red-50': toast.type === 'error',
                    'bg-yellow-50': toast.type === 'warning'
                }"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Success Icon -->
                            <i x-show="toast.type === 'success'" data-lucide="check-circle" class="h-6 w-6 text-green-400"></i>
                            <!-- Error Icon -->
                            <i x-show="toast.type === 'error'" data-lucide="x-circle" class="h-6 w-6 text-red-400"></i>
                            <!-- Warning Icon -->
                            <i x-show="toast.type === 'warning'" data-lucide="alert-triangle" class="h-6 w-6 text-yellow-400"></i>
                            <!-- Info Icon -->
                            <i x-show="toast.type === 'info'" data-lucide="info" class="h-6 w-6 text-blue-400"></i>
                        </div>
                        <div class="ml-3 flex-shrink-0 pt-0.5">
                            <p 
                                class="text-sm font-medium whitespace-nowrap"
                                :class="{
                                    'text-gray-900': toast.type === 'info',
                                    'text-green-800': toast.type === 'success',
                                    'text-red-800': toast.type === 'error',
                                    'text-yellow-800': toast.type === 'warning'
                                }"
                                x-text="toast.message"
                            ></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button 
                                @click="$store.ui.removeToast(toast.id)"
                                class="rounded-md inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="{
                                    'text-gray-400 hover:text-gray-500 focus:ring-indigo-500': toast.type === 'info',
                                    'text-green-400 hover:text-green-500 focus:ring-green-500': toast.type === 'success',
                                    'text-red-400 hover:text-red-500 focus:ring-red-500': toast.type === 'error',
                                    'text-yellow-400 hover:text-yellow-500 focus:ring-yellow-500': toast.type === 'warning'
                                }"
                            >
                                <span class="sr-only">Close</span>
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Global Modal -->
    <x-modal />

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">© 2025 KaiBook.</p>
        </div>
    </footer>

    <style>
        .nav-link {
            @apply whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-all duration-200;
        }
    </style>
</body>
</html>
