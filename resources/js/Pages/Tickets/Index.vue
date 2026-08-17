<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    tickets: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}) 
    }
}); 

// The function that runs when a user clicks a filter tab
const applyFilter = (statusValue) => {
    // Send an AJAX GET request to the index, passing the status parameter. 
    // preserveState and preserveScroll ensure the page doesn't jarringly refresh. 
    router.get(route('tickets.index'), { status: statusValue}, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Support Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Support Tickets</h2>
                <Link :href="route('tickets.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition shadow-sm text-sm">
                    + Create Ticket
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6 text-gray-900">
                        <!--Filter Controls-->
                        <div class="flex space-x-6 mb-6 border-b border-gray-200 pb-3">
                            <button
                            @click="applyFilter(null)"
                            :class="!filters.status ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-3 -mb-3.5' : 'text-gray-500 hover:text-gray-800 font-medium pb-3 -mb-3.5 transition'">
                            All Tickets
                            </button>
                            <button
                            @click="applyFilter('open')"
                            :class="filters.status === 'open' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-3 -mb-3.5' : 'text-gray-500 hover:text-gray-800 font-medium pb-3 -mb-3.5 transition'">
                            Open
                            </button>
                            <button
                            @click="applyFilter('in_progress')"
                            :class="filters.status === 'in_progress' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-3 -mb-3.5' : 'text-gray-500 hover:text-gray-800 font-medium pb-3 -mb-3.5 transition'">
                            In Progress 
                            </button>
                            <button
                            @click="applyFilter('closed')"
                            :class="filters.status === 'closed' ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-3 -mb-3.5' : 'text-gray-500 hover:text-gray-800 font-medium pb-3 -mb-3.5 transition'">
                            Closed 
                            </button>
                        </div>

                        <!--Modern Data Table-->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!--Loop through the JSON Contract data-->
                                    <tr v-for="ticket in tickets.data" :key="ticket.id" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #{{ ticket.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ ticket.title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ ticket.customer?.name || 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="['px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full border', ticket.status.color]">
                                                {{ ticket.status.label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="['px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full border', ticket.priority.color]">
                                                {{ ticket.priority.label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ ticket.created_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('tickets.show', ticket.id)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                                View &rarr;
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="tickets.data.length === 0">
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 font-medium">
                                            No support ticket found in the database. 
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>