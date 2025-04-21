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
    <Head title="Pagina inicial" />
    <header class="w-full bg-white fixed">
        <div class="w-11/12 px-3 flex mx-auto justify-between items-center">
            <img src="/images/logo.jpg" class="w-[85px]" />
            <nav class="navigation-items">
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Inicio</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Sobre nosotros</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Especialidades</Link>
                <Link class="mx-3 hover:border-b-2 hover:border-b-violet-400 hover:text-violet-400" href="/">Contacto</Link>
                <Link class="mx-2 px-2 py-2 bg-violet-400 text-white hover:bg-violet-600 hover:ease-in" href="/">Administracion</Link>
            </nav>
        </div>
    </header>
</template>
<style>
body {
    background-color: rgb(227, 206, 250);
}
</style>    