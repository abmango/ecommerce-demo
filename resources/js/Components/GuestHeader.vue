<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <header class="w-full bg-white fixed shadow-sm">
        <div class="w-11/12 px-3 flex mx-auto justify-between items-center">
            <img src="/images/logo.jpg" class="w-[85px]" />
            <nav class="navigation-items">
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Inicio</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Sobre nosotros</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Especialidades</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Contacto</Link>
                <Link class="mx-2 px-2 py-2 bg-violet-400 text-white hover:bg-violet-600 hover:ease-in" :href="route('login')">Administracion</Link>
            </nav>
        </div>
    </header>
</template>