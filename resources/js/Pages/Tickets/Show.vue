<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

// Access the global Inertia page props to check the logged-in user's role. 
const page = usePage();
const isAgent = computed(() => page.props.auth.user.role === 'agent');

// 1. Comment form 
const commentForm = useForm({
    comment: '',
});

const submitComment = () => {
    // Post to skinny controller route.
    commentForm.post(route('tickets.comments.store', props.ticket.data.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset('comment'),
    });
};

// 2. The status change form (agents only)
const statusForm = useForm({
    status: '',
});

const changeStatus = (newStatus) => {
    statusForm.status = newStatus;
    // patch to skinny controller route. 
    statusForm.patch(route('tickets.status.update', props.ticket.data.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Ticket #${ticket.data.id}`"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4">
                <Link :href="route('tickets.index')" class="text-gray-500 hover:text-indigo-600 transition">&larr; Back</Link>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Ticket #{{ ticket.data.id }}: {{ ticket.data.title }}
                </h2>
                <!--Backend Enum CSS injection-->
                <span :class="['px-3 py-1 text-xs font-bold rounded0-full border', ticket.data.status.color]">
                    {{ ticket.data.status.label }}
                </span>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!--CSS Grid: 2 columns on left for details, 1 column on right for timeline-->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!--LEFT COLUMN: Ticket details & Comment box-->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!--Original Ticket Details-->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ ticket.data.id }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Opened by <span class="font-semibold text-gray-700">{{ ticket.data.customer?.name }}</span>
                                    </p>
                                </div>
                                <span :class="['px-3 py-1 text-xs font-bold rounded-full border'], ticket.data.priority.color">
                                    Priority: {{ ticket.data.priority.label }}
                                </span>

                            </div>

                            <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-md border border-gray-100">
                                {{ ticket.data.description }}
                            </div> 
                        </div>

                        <!--Submit New Comment Box-->
                        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <h4 class="font-bold text-gray-800">Add a Comment</h4>
                            </div>
                            <div class="p-4">
                                <form @submit.prevent="submitComment">
                                    <textarea
                                    v-model="commentForm.comment"
                                    rows="4"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Type your reply here..."
                                    required
                                    ></textarea> 
                                    <div class="mt-2 text-red-500 text-sm" v-if="commentForm.errors.comment">{{ commentForm.errors.comment }}
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end">
                                        <button 
                                        type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition disabled:opacity-50"
                                        :disabled="commentForm.processing"
                                        >
                                        {{ commentForm.processing? 'Posting...' : 'Post Reply' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--RIGHT COLUMN: Agent Action & Activity Timeline-->
                    <div class="space-y-6">
                        <!--Agent Control Panel (Only agent can access)-->
                        <div v-if="isAgent" class="bg-indigo-50 shadow-sm sm:rounded-lg border border-indigo-100 p-5">
                            <h4 class="font-bold text-indigo-900 mb-3 text-sm uppercase tracking-wider">Agent Controls</h4>
                            <div class="space-y-2">
                                <p class="text-xs text-indigo-700 font-medium mb-2">Change Status:</p>
                                <div class="flex space-x-2">
                                    <button @click="changeStatus('open')" :disabled="statusForm.processing || ticket.data.status.value === 'open'" class="flex-1 bg-white hover:bg-blue-50 text-blue-700 font-bold py-1.5 px-2 border border-blue-200 rounded text-xs transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        Open  
                                    </button>
                                    <button @click="changeStatus('in_progress')" :disabled="statusForm.processing || ticket.data.status.value === 'in_progress'" class="flex-1 bg-white hover:bg-yellow-50 text-yellow-700 font-bold py-1.5 px-2 border border-yellow-200 rounded text-xs transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        In Progress 
                                    </button>
                                    <button @click="changeStatus('closed')" :disabled="statusForm.processing || ticket.data.status.value === 'closed'" class="flex-1 bg-white hover:bg-green-50 text-green-700 font-bold py-1.5 px-2 border border-green-200 rounded text-xs transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        Closed 
                                    </button>
                                </div>
                            </div>
                        </div> 

                        <!--Activity Timeline-->
                        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-5">
                            <h4 class="font-bold text-gray-900 mb-4 border-b pb-2">Activity Timeline</h4>

                            <div class="relative">
                                <!--Vertical line going through timeline-->
                                <div class="absolute top-0 bottom-0 left-4 w-0.5 bg-gray-200"></div>
                                <ul class="space-y-6 relative">
                                    <!--Loop through the event sourced audit trail-->
                                    <li v-for="activity in ticket.data.activities" :key="activity.id" class="relative pl-10">
                                        <!--Timeline Dot-->
                                        <div class="absolute left-2.5 top-1 w-3.5 h-3.5 rounded-full border-2 border-white" 
                                            :class="activity.type === 'comment_added' ? 'bg-indigo-500' : 'bg-gray-400'">
                                        </div>

                                        <!--Event Content-->
                                        <div class="text-sm">

                                            <!--If it's a comment-->
                                            <div v-if="activity.type === 'comment_added'" class="bg-gray-50 border border-gray-200 rounded-md p-3">
                                                <div class="font-semibold text-gray-900 mb-1">
                                                    {{ activity.actor?.name }} <span class="text-gray-500 font-normal text-xs ml-1">{{ activity.created_at }}</span>
                                                </div>
                                                <div class="text-gray-700">
                                                    {{ activity.comment }}
                                                </div> 
                                            </div>

                                            <!--If it's a system event-->
                                            <div v-else>
                                                <span class="font-semibold text-gray-900">{{ activity.actor?.name || 'System' }}</span>
                                                <span class="text-gray-600 ml-1">{{ activity.comment }}</span>
                                                <div class="text-gray-400 text-xs mt-0.5">{{ activity.created_at }} </div>
                                            </div>                                                
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

