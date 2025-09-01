@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="profilePage()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3">
                <i data-lucide="user" class="w-8 h-8 text-blue-600"></i>
                <span>Profile</span>
            </h1>
            <p class="mt-2 text-gray-600">Manage your account information and security settings</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info Card -->
            <div class="lg:col-span-1">
                <x-card class="text-center">
                    <div class="p-6">
                        <!-- Avatar -->
                        <div class="w-24 h-24 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i data-lucide="user" class="w-12 h-12 text-blue-600"></i>
                        </div>
                        
                        <!-- User Info -->
                        <h3 class="text-xl font-semibold text-gray-900" x-text="userInfo.name"></h3>
                        <p class="text-gray-600 mt-1" x-text="userInfo.email"></p>
                        <p class="text-sm text-gray-500 mt-2" x-text="userInfo.memberSince ? ('Member since ' + userInfo.memberSince) : ''"></p>
                        
                        <!-- Quick Stats -->
                        <div class="mt-6 grid grid-cols-2 gap-4 text-center">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-blue-600">12</div>
                                <div class="text-xs text-gray-600">Total Bookings</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-green-600">8</div>
                                <div class="text-xs text-gray-600">Completed</div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Forms Section -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Edit Profile Form -->
                <x-card>
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <i data-lucide="edit-3" class="w-6 h-6 text-gray-600"></i>
                            <h2 class="text-xl font-semibold text-gray-900">Edit Profile</h2>
                        </div>

                        <form @submit.prevent="updateProfile()" class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
                                    <div class="mt-2">
                                        <input 
                                            type="text" 
                                            name="name" 
                                            id="name" 
                                            x-model="profileForm.name"
                                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                            placeholder="Enter your full name"
                                            required
                                        >
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                                    <div class="mt-2">
                                        <input 
                                            type="email" 
                                            name="email" 
                                            id="email" 
                                            x-model="profileForm.email"
                                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                            placeholder="Enter your email address"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105"
                                    :disabled="isLoading"
                                >
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span x-text="isLoading ? 'Saving...' : 'Save Profile'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </x-card>

                <!-- Change Password Form -->
                <x-card>
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <i data-lucide="lock" class="w-6 h-6 text-gray-600"></i>
                            <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
                        </div>

                        <form @submit.prevent="changePassword()" class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- New Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">New Password</label>
                                    <div class="mt-2">
                                        <input 
                                            type="password" 
                                            name="password" 
                                            id="password" 
                                            x-model="passwordForm.password"
                                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                            placeholder="Enter new password"
                                            required
                                        >
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                                    <div class="mt-2">
                                        <input 
                                            type="password" 
                                            name="password_confirmation" 
                                            id="password_confirmation" 
                                            x-model="passwordForm.password_confirmation"
                                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                            placeholder="Confirm new password"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex">
                                    <i data-lucide="info" class="w-5 h-5 text-blue-400 mt-0.5"></i>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Password Requirements</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>At least 8 characters long</li>
                                                <li>Include uppercase and lowercase letters</li>
                                                <li>Include at least one number</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password Button -->
                            <div class="flex justify-end">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center space-x-2 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105"
                                    :disabled="isLoading"
                                >
                                    <i data-lucide="key" class="w-4 h-4"></i>
                                    <span x-text="isLoading ? 'Updating...' : 'Change Password'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

<script>
function profilePage() {
    return {
        isLoading: false,
        userInfo: {
            name: '',
            email: '',
            memberSince: ''
        },
        profileForm: {
            name: '',
            email: ''
        },
        passwordForm: {
            password: '',
            password_confirmation: ''
        },

        init() {
            this.loadUser();
        },

        async loadUser() {
            try {
                const data = await window.authAPI.getUser();
                this.userInfo.name = data?.name || '';
                this.userInfo.email = data?.email || '';
                if (data?.created_at) {
                    const d = new Date(data.created_at);
                    this.userInfo.memberSince = isNaN(d) ? '' : d.toLocaleDateString();
                }
                this.profileForm.name = this.userInfo.name;
                this.profileForm.email = this.userInfo.email;
                // keep store/display name in sync where used elsewhere
                if (this.$store?.auth) {
                    this.$store.auth.userName = this.userInfo.name;
                    localStorage.setItem('mockUserName', this.userInfo.name);
                }
            } catch (error) {
                this.$store.ui.addToast('error', window.handleApiError(error, 'Failed to load profile'));
            }
        },

        async updateProfile() {
            this.isLoading = true;
            const payload = {
                name: this.profileForm.name,
                email: this.profileForm.email
            };
            try {
                await window.profileAPI.updateProfile(payload);
                this.userInfo.name = this.profileForm.name;
                this.userInfo.email = this.profileForm.email;
                if (this.$store?.auth) {
                    this.$store.auth.userName = this.profileForm.name;
                    localStorage.setItem('mockUserName', this.profileForm.name);
                }
                this.$store.ui.addToast('success', 'Profile updated successfully!');
            } catch (error) {
                this.$store.ui.addToast('error', window.handleApiError(error, 'Failed to update profile'));
            } finally {
                this.isLoading = false;
            }
        },

        async changePassword() {
            if (this.passwordForm.password !== this.passwordForm.password_confirmation) {
                this.$store.ui.addToast('error', 'Passwords do not match!');
                return;
            }
            this.isLoading = true;
            const payload = {
                password: this.passwordForm.password,
                password_confirmation: this.passwordForm.password_confirmation
            };
            try {
                await window.profileAPI.changePassword(payload);
                this.passwordForm.password = '';
                this.passwordForm.password_confirmation = '';
                this.$store.ui.addToast('success', 'Password changed successfully!');
            } catch (error) {
                this.$store.ui.addToast('error', window.handleApiError(error, 'Failed to change password'));
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
@endsection
