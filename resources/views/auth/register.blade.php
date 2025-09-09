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
                showTermsModal: false,
                showPrivacyModal: false,
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
                        <a href="#" @click.prevent="showTermsModal = true" class="font-medium text-blue-600 hover:text-blue-500">Terms of Service</a>
                        and 
                        <a href="#" @click.prevent="showPrivacyModal = true" class="font-medium text-blue-600 hover:text-blue-500">Privacy Policy</a>
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

                <!-- Terms of Service Modal -->
                <div 
                    x-show="showTermsModal"
                    x-transition.opacity
                    @keydown.escape.window="showTermsModal = false"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8"
                    aria-labelledby="modal-title-terms"
                    aria-modal="true"
                    role="dialog"
                >
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-900/60" @click="showTermsModal = false"></div>

                    <!-- Panel -->
                    <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-xl">
                        <div class="flex items-start justify-between border-b px-5 py-4">
                            <h3 id="modal-title-terms" class="text-lg font-semibold text-gray-900">KaiBook – Terms of Service</h3>
                            <button @click="showTermsModal = false" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="max-h-[70vh] overflow-y-auto px-5 py-4 text-sm leading-6 text-gray-800">
                            <p><strong>Effective Date:</strong> September 9, 2025</p>
                            <ol class="list-decimal pl-5 space-y-3 mt-3">
                                <li>
                                    <strong>Acceptance of Terms</strong><br>
                                    By accessing or using KaiBook’s website (the “Site”), you agree to these Terms of Service. If you do not agree, please do not use the Site.
                                </li>
                                <li>
                                    <strong>Eligibility & Accounts</strong><br>
                                    You must be at least [13/16/18] depending on your region.<br>
                                    You are responsible for keeping your login credentials secure.
                                </li>
                                <li>
                                    <strong>Services Provided</strong><br>
                                    KaiBook allows users to search, book, and manage reservations online. We act as an intermediary platform; the actual services are provided by third-party providers.
                                </li>
                                <li>
                                    <strong>Payments & Refunds</strong><br>
                                    Payments may be processed via third-party gateways (e.g., Stripe, PayPal).<br>
                                    Refunds or cancellations are subject to provider policies, shown at checkout.
                                </li>
                                <li>
                                    <strong>User Conduct</strong><br>
                                    You agree not to:
                                    <ul class="list-disc pl-5">
                                        <li>Post false information or fake bookings.</li>
                                        <li>Attempt to hack, disrupt, or overload the Site.</li>
                                        <li>Misuse the Site for unlawful purposes.</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Intellectual Property</strong><br>
                                    All content on the Site (logos, design, text, software) is owned by KaiBook unless stated otherwise. You may not copy or redistribute without permission.
                                </li>
                                <li>
                                    <strong>Third-Party Links</strong><br>
                                    The Site may link to third-party websites. We are not responsible for their content or practices.
                                </li>
                                <li>
                                    <strong>Disclaimer of Warranties</strong><br>
                                    The Site and services are provided “as is,” with no guarantees regarding accuracy, availability, or reliability.
                                </li>
                                <li>
                                    <strong>Limitation of Liability</strong><br>
                                    KaiBook will not be liable for indirect or consequential damages. Liability is capped at the amount paid in the past 12 months.
                                </li>
                                <li>
                                    <strong>Indemnification</strong><br>
                                    You agree to indemnify KaiBook from any claims or damages arising from your misuse of the Site.
                                </li>
                                <li>
                                    <strong>Governing Law & Disputes</strong><br>
                                    These Terms are governed by [Insert Country/State Law]. Disputes will be resolved through [arbitration / courts] in [Jurisdiction].
                                </li>
                                <li>
                                    <strong>Modifications</strong><br>
                                    We may update these Terms at any time. Continued use of the Site means you accept the new Terms.
                                </li>
                                <li>
                                    <strong>Contact</strong><br>
                                    KaiBook<br>
                                    Magelang City<br>
                                    📧 <a href="mailto:penyimpananwarid@gmail.com" class="text-blue-600 hover:underline">penyimpananwarid@gmail.com</a>
                                </li>
                            </ol>
                        </div>
                        <div class="flex justify-end gap-2 border-t px-5 py-3">
                            <button @click="showTermsModal = false" class="rounded-md border px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Modal -->
                <div 
                    x-show="showPrivacyModal"
                    x-transition.opacity
                    @keydown.escape.window="showPrivacyModal = false"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8"
                    aria-labelledby="modal-title-privacy"
                    aria-modal="true"
                    role="dialog"
                >
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-900/60" @click="showPrivacyModal = false"></div>

                    <!-- Panel -->
                    <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-xl">
                        <div class="flex items-start justify-between border-b px-5 py-4">
                            <h3 id="modal-title-privacy" class="text-lg font-semibold text-gray-900">KaiBook – Privacy Policy</h3>
                            <button @click="showPrivacyModal = false" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="max-h-[70vh] overflow-y-auto px-5 py-4 text-sm leading-6 text-gray-800">
                            <p><strong>Last Updated:</strong> September 9, 2025</p>
                            <ol class="list-decimal pl-5 space-y-3 mt-3">
                                <li>
                                    <strong>Introduction</strong><br>
                                    KaiBook (“we,” “our,” “us”) values your privacy. This Privacy Policy explains how we collect, use, and protect your information when you use our website.
                                </li>
                                <li>
                                    <strong>Information We Collect</strong><br>
                                    Account Info: name, email, phone number, password.<br>
                                    Booking Data: reservation details, payment confirmations.<br>
                                    Device & Browser Data: IP address, browser type, cookies, log data.<br>
                                    Location Data: if you grant permission.
                                </li>
                                <li>
                                    <strong>How We Use Data</strong><br>
                                    To provide and manage bookings.<br>
                                    To personalize your experience.<br>
                                    To send booking confirmations, updates, or promotional emails (opt-in required).<br>
                                    To analyze traffic and improve services.
                                </li>
                                <li>
                                    <strong>Cookies & Tracking</strong><br>
                                    We use cookies and similar technologies to:
                                    <ul class="list-disc pl-5">
                                        <li>Keep you logged in.</li>
                                        <li>Measure traffic and usage.</li>
                                        <li>Serve relevant content.</li>
                                    </ul>
                                    Users can manage cookies in browser settings.
                                </li>
                                <li>
                                    <strong>Data Sharing</strong><br>
                                    We share data only with:
                                    <ul class="list-disc pl-5">
                                        <li>Service providers (payment, hosting, analytics).</li>
                                        <li>Legal authorities if required by law.</li>
                                        <li>Business transfers (merger/acquisition).</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Security & Retention</strong><br>
                                    We apply encryption, secure servers, and limited access. Data is kept as long as needed for business or legal purposes.
                                </li>
                                <li>
                                    <strong>Your Rights</strong><br>
                                    Depending on your location (GDPR, CCPA, etc.), you may:
                                    <ul class="list-disc pl-5">
                                        <li>Access, update, or delete your data.</li>
                                        <li>Opt out of marketing.</li>
                                        <li>Request data portability.</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Children’s Privacy</strong><br>
                                    KaiBook is not intended for users under 13 (or 16 in some regions). We do not knowingly collect data from minors.
                                </li>
                                <li>
                                    <strong>Changes to Policy</strong><br>
                                    We may update this Policy. Updates will be posted with a new “Last Updated” date.
                                </li>
                                <li>
                                    <strong>Contact Us</strong><br>
                                    KaiBook<br>
                                    Magelang City<br>
                                    📧 <a href="mailto:penyimpananwarid@gmail.com" class="text-blue-600 hover:underline">penyimpananwarid@gmail.com</a>
                                </li>
                            </ol>
                        </div>
                        <div class="flex justify-end gap-2 border-t px-5 py-3">
                            <button @click="showPrivacyModal = false" class="rounded-md border px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Close</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
