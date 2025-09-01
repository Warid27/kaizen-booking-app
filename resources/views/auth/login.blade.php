{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - KaiBook')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            Sign in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="/register" class="font-medium text-blue-600 hover:text-blue-500">
                create a new account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" @submit.prevent="login" x-data="{ 
                email: '',
                password: '',
                isLoading: false,
                errorMessage: '',
                async login() {
                    this.isLoading = true;
                    this.errorMessage = '';
                    
                    try {
                        const result = await $store.auth.login({
                            email: this.email,
                            password: this.password
                        });

                        if (result && result.success) {
                            window.location.href = '/dashboard';
                        } else {
                            this.errorMessage = (result && result.error) ? result.error : 'Login failed. Please check your credentials.';
                        }
                    } catch (error) {
                        this.errorMessage = window.handleApiError(error, 'An error occurred during login. Please try again.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }">
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
                            autocomplete="current-password" 
                            required 
                            x-model="password"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                        >
                    </div>
                </div>

                <div x-show="errorMessage" class="text-sm text-red-600">
                    <span x-text="errorMessage"></span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember-me" 
                            name="remember-me" 
                            type="checkbox" 
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                        >
                        <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm leading-6">
                        <a href="/password/reset" class="font-semibold text-blue-600 hover:text-blue-500">Forgot password?</a>
                    </div>
                </div>

                <div>
                    <button 
                        type="submit"
                        :disabled="isLoading"
                        class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50"
                    >
                        <span x-text="isLoading ? 'Signing in...' : 'Sign in'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection