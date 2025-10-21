<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import GuestHeader from '@/Components/GuestHeader.vue';

defineProps({
    title: String,
});

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <GuestHeader />

        <div class="min-h-screen bg-gray-100 pt-20">
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
