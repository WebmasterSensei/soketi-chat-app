
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div>

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notification</h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div v-for="notif in notifs" :id="notif.id">
                                <h1>{{ notif.message }}</h1>
                            </div>
                            <form class="ui form" @submit.prevent="submitForm()">

                                <div class="field">
                                    <label>Message</label>
                                    <textarea v-model="form.message" @input="typeMessage" placeholder="Message here..."
                                        rows="2" required></textarea>
                                </div>

                                <button class="ui button">Send</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>


<script>
import { useForm, Head } from '@inertiajs/vue3';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
    data() {
        return {
            form: useForm({
                message: '',
            }),

        };
    },
    props: {
        notifs: Array,
    },
    methods: {
        submitForm() {
            this.form.post(route('notif.store'), {
                onSuccess: () => {
                    this.form.reset();
                },
            });
        },
    },
    mounted() {

        const appendArray = (data) => {
            this.notifs.push({ 'id': data.message_id, 'message': data.message, 'user': { 'name': data.user } });
        };
        if (window.Echo && this.$page.props.auth.user.id) {
                const presenceChannel = window.Echo.join(`presence`);

                presenceChannel.here((users) => {
                    console.log("Here -", users);
                });

                presenceChannel.joining((user) => {
                    console.log("Joining -", user.name);
                });

                presenceChannel.leaving((user) => {
                    console.log("Leaving -", user.name);
                });

                presenceChannel.listen('.my-event', function (data) {
                    appendArray(data);
                    console.log(data);
                });
            } else {
                console.error('Laravel Echo is not available!');
            }



    },
};
</script>

