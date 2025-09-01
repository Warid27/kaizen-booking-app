{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register - KaiBook')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">
                sign in to your existing account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="#" x-data="{ 
                name: '', 
                email: '', 
                password: '',
                password_confirmation: '',
                isLoading: false,
                async createAccount() {
                    if (this.password !== this.password_confirmation) {
                        this.$store.ui.addToast('error', 'Passwords do not match!');
                        return;
                    }

                    this.isLoading = true;
                    
                    try {
                        const result = await this.$store.auth.register({
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        });
                        
                        if (result.success) {
                            this.$store.ui.addToast('success', 'Registration successful!');
                            window.location.href = '/dashboard';
                        } else {
                            this.$store.ui.addToast('error', result.error || 'Registration failed');
                        }
                    } catch (error) {
                        this.$store.ui.addToast('error', window.handleApiError(error, 'An unexpected error occurred'));
                    } finally {
                        this.isLoading = false;
                    }
                }
            }">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full name</label>
                    <div class="mt-2">
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            required 
                            x-model="name"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                        >
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            x-model="email"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            x-model="password"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                        >
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm password</label>
                    <div class="mt-2">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            x-model="password_confirmation"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                        >
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                    >
                    <label for="terms" class="ml-3 block text-sm leading-6 text-gray-900">
                        I agree to the 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Terms of Service</a>
                        and 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Privacy Policy</a>
                    </label>
                </div>

                <div>
                    <button 
                        type="button"
                        @click="createAccount()"
                        :disabled="isLoading"
                        class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        <span x-text="isLoading ? 'Creating Account...' : 'Create Account'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
