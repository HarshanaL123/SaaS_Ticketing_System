<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome to CeylonByte Support" />

    <div class="bg-gray-50 text-gray-800 min-h-screen selection:bg-indigo-500 selection:text-white">
        <!-- Navigation -->
        <nav class="absolute w-full z-10 px-6 py-4 flex justify-between items-center max-w-7xl mx-auto inset-x-0">
            <div class="flex items-center gap-2">
                <!-- Abstract Cube Logo -->
                <svg class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-black text-xl tracking-tight text-gray-900">CeylonByte<span class="text-indigo-600">.</span></span>
            </div>
            <div v-if="canLogin" class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('tickets.index')"
                    class="font-semibold text-gray-600 hover:text-indigo-600 transition-colors"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="font-semibold text-gray-600 hover:text-indigo-600 transition-colors"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg shadow-sm transition-all"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 lg:pb-32 overflow-hidden">
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight text-gray-900 mb-8">
                    Modern support for <br class="hidden sm:block"/> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">modern teams.</span>
                </h1>
                <p class="mt-4 max-w-2xl text-lg sm:text-xl text-gray-500 mx-auto mb-10">
                    A strictly-typed, domain-driven support desk. Featuring event-sourced audit trails, asynchronous queues, and real-time Vue 3 reactivity.
                </p>
                <div class="flex justify-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('tickets.index')"
                        class="px-8 py-3.5 text-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg hover:shadow-xl transition-all"
                    >
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
