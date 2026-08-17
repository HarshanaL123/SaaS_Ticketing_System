<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    priority: 'medium', // Default priority
});

const submit = () => {
    form.post(route('tickets.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4">
                <Link :href="route('tickets.index')" class="text-gray-500 hover:text-indigo-600 transition">&larr; Back</Link>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Create New Ticket
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Title Field -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Ticket Title</label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    v-model="form.title" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Brief summary of the issue"
                                    required
                                >
                                <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
                                <textarea 
                                    id="description" 
                                    v-model="form.description" 
                                    rows="6" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Provide as much detail as possible..."
                                    required
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                            </div>

                            <!-- Priority Field -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority Level</label>
                                <select 
                                    id="priority" 
                                    v-model="form.priority" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option value="low">Low - General inquiry</option>
                                    <option value="medium">Medium - Issue affecting workflow</option>
                                    <option value="high">High - Critical system failure</option>
                                </select>
                                <div v-if="form.errors.priority" class="text-red-500 text-sm mt-1">{{ form.errors.priority }}</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4 border-t border-gray-100">
                                <button 
                                    type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition shadow-sm disabled:opacity-50"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Submitting...' : 'Submit Ticket' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
