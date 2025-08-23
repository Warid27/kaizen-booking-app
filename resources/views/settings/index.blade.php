@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="settingsPage()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3">
                <i data-lucide="settings" class="w-8 h-8 text-blue-600"></i>
                <span>Settings</span>
            </h1>
            <p class="mt-2 text-gray-600">Manage your application preferences and account settings</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Notification Settings -->
            <x-card>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                        <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Email Notifications Toggle -->
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Email Notifications</h3>
                                <p class="text-sm text-gray-500">Receive booking confirmations and updates via email</p>
                            </div>
                            <div class="ml-4">
                                <button 
                                    @click="toggleNotifications()"
                                    :class="settings.notifications ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                    role="switch"
                                    :aria-checked="settings.notifications"
                                >
                                    <span 
                                        :class="settings.notifications ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <!-- Push Notifications Toggle -->
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Push Notifications</h3>
                                <p class="text-sm text-gray-500">Get instant notifications on your device</p>
                            </div>
                            <div class="ml-4">
                                <button 
                                    @click="togglePushNotifications()"
                                    :class="settings.pushNotifications ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                    role="switch"
                                    :aria-checked="settings.pushNotifications"
                                >
                                    <span 
                                        :class="settings.pushNotifications ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Appearance Settings -->
            <x-card>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <i data-lucide="palette" class="w-6 h-6 text-gray-600"></i>
                        <h2 class="text-xl font-semibold text-gray-900">Appearance</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Theme Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-3">Theme</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    @click="setTheme('light')"
                                    :class="settings.theme === 'light' ? 'ring-2 ring-blue-600 bg-blue-50' : 'ring-1 ring-gray-300'"
                                    class="relative rounded-lg p-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-200"
                                >
                                    <div class="flex items-center space-x-3">
                                        <i data-lucide="sun" class="w-5 h-5 text-yellow-500"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Light</div>
                                            <div class="text-xs text-gray-500">Default theme</div>
                                        </div>
                                    </div>
                                    <div x-show="settings.theme === 'light'" class="absolute top-2 right-2">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                                    </div>
                                </button>

                                <button 
                                    @click="setTheme('dark')"
                                    :class="settings.theme === 'dark' ? 'ring-2 ring-blue-600 bg-blue-50' : 'ring-1 ring-gray-300'"
                                    class="relative rounded-lg p-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-200"
                                >
                                    <div class="flex items-center space-x-3">
                                        <i data-lucide="moon" class="w-5 h-5 text-indigo-500"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Dark</div>
                                            <div class="text-xs text-gray-500">Coming soon</div>
                                        </div>
                                    </div>
                                    <div x-show="settings.theme === 'dark'" class="absolute top-2 right-2">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Language & Region -->
            <x-card>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <i data-lucide="globe" class="w-6 h-6 text-gray-600"></i>
                        <h2 class="text-xl font-semibold text-gray-900">Language & Region</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Language Selection -->
                        <div>
                            <label for="language" class="block text-sm font-medium leading-6 text-gray-900">Language</label>
                            <div class="mt-2">
                                <select 
                                    id="language" 
                                    name="language" 
                                    x-model="settings.language"
                                    @change="updateLanguage()"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option value="english">🇺🇸 English</option>
                                    <option value="indonesian">🇮🇩 Indonesian (Bahasa Indonesia)</option>
                                    <option value="spanish">🇪🇸 Spanish (Español)</option>
                                    <option value="french">🇫🇷 French (Français)</option>
                                </select>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Choose your preferred language for the interface</p>
                        </div>

                        <!-- Timezone -->
                        <div>
                            <label for="timezone" class="block text-sm font-medium leading-6 text-gray-900">Timezone</label>
                            <div class="mt-2">
                                <select 
                                    id="timezone" 
                                    name="timezone" 
                                    x-model="settings.timezone"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option value="UTC">UTC (Coordinated Universal Time)</option>
                                    <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                                    <option value="America/New_York">America/New_York (EST)</option>
                                    <option value="Europe/London">Europe/London (GMT)</option>
                                    <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Privacy & Security -->
            <x-card>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <i data-lucide="shield" class="w-6 h-6 text-gray-600"></i>
                        <h2 class="text-xl font-semibold text-gray-900">Privacy & Security</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Data Sharing Toggle -->
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Analytics & Usage Data</h3>
                                <p class="text-sm text-gray-500">Help improve our service by sharing anonymous usage data</p>
                            </div>
                            <div class="ml-4">
                                <button 
                                    @click="toggleAnalytics()"
                                    :class="settings.analytics ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                    role="switch"
                                    :aria-checked="settings.analytics"
                                >
                                    <span 
                                        :class="settings.analytics ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <!-- Marketing Emails Toggle -->
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Marketing Communications</h3>
                                <p class="text-sm text-gray-500">Receive promotional emails and special offers</p>
                            </div>
                            <div class="ml-4">
                                <button 
                                    @click="toggleMarketing()"
                                    :class="settings.marketing ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                    role="switch"
                                    :aria-checked="settings.marketing"
                                >
                                    <span 
                                        :class="settings.marketing ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Save Settings Button -->
        <div class="mt-8 flex justify-center">
            <button 
                @click="saveSettings()"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105"
                :disabled="isLoading"
            >
                <i data-lucide="save" class="w-5 h-5"></i>
                <span x-text="isLoading ? 'Saving...' : 'Save Settings'"></span>
            </button>
        </div>
    </div>
</div>

<script>
function settingsPage() {
    return {
        isLoading: false,
        settings: {
            notifications: true,
            pushNotifications: false,
            theme: 'light',
            language: 'english',
            timezone: 'Asia/Jakarta',
            analytics: true,
            marketing: false
        },

        init() {
            // Load settings from localStorage if available
            const savedSettings = localStorage.getItem('userSettings');
            if (savedSettings) {
                this.settings = { ...this.settings, ...JSON.parse(savedSettings) };
            }
        },

        toggleNotifications() {
            this.settings.notifications = !this.settings.notifications;
            this.showToggleToast('Email notifications', this.settings.notifications);
        },

        togglePushNotifications() {
            this.settings.pushNotifications = !this.settings.pushNotifications;
            this.showToggleToast('Push notifications', this.settings.pushNotifications);
        },

        toggleAnalytics() {
            this.settings.analytics = !this.settings.analytics;
            this.showToggleToast('Analytics & usage data', this.settings.analytics);
        },

        toggleMarketing() {
            this.settings.marketing = !this.settings.marketing;
            this.showToggleToast('Marketing communications', this.settings.marketing);
        },

        setTheme(theme) {
            this.settings.theme = theme;
            this.$store.ui.addToast('info', `Theme changed to ${theme}`);
        },

        updateLanguage() {
            const languageNames = {
                'english': 'English',
                'indonesian': 'Indonesian',
                'spanish': 'Spanish',
                'french': 'French'
            };
            this.$store.ui.addToast('info', `Language changed to ${languageNames[this.settings.language]}`);
        },

        showToggleToast(setting, enabled) {
            const status = enabled ? 'enabled' : 'disabled';
            this.$store.ui.addToast('info', `${setting} ${status}`);
        },

        saveSettings() {
            this.isLoading = true;
            
            // Mock API payload
            const payload = {
                notifications: this.settings.notifications,
                pushNotifications: this.settings.pushNotifications,
                theme: this.settings.theme,
                language: this.settings.language,
                timezone: this.settings.timezone,
                analytics: this.settings.analytics,
                marketing: this.settings.marketing
            };
            
            console.log('Settings Save Payload:', JSON.stringify(payload, null, 2));
            
            // Simulate API call
            setTimeout(() => {
                // Save to localStorage
                localStorage.setItem('userSettings', JSON.stringify(this.settings));
                
                this.isLoading = false;
                
                // Show success toast
                this.$store.ui.addToast('success', 'Settings saved successfully!');
            }, 1000);
        }
    }
}
</script>
@endsection
