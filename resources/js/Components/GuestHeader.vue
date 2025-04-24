<script setup>
import { Link } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const navItems = reactive([
    { type: 'normal', name : 'Inicio', href : '/' },
    { type: 'normal', name : 'Nosotros', href : '/' },
    { type: 'normal', name : 'Especialidades', href : '/' },
    { type: 'normal', name : 'Contacto', href : '/' },
    { type: 'normal', name : 'Administracion', href : route('login'), class : 'bg-indigo-500 text-white p-2 ms-1 rounded-sm hover:text-white hover:border-none w-auto' },
    { 
      type : 'icon', name : 'fas fa-cart-shopping', 
      href : route('cart.checkout'),
      class: 'text-indigo-500',
      text: 'Mi carrito'
    }
])

const isMenuVisible = ref(false)
const toggleMenu = () => { 
    isMenuVisible.value = !isMenuVisible.value
}
</script>
<template>
    <header class="w-full bg-white fixed shadow-sm top-0 start-0 z-30">
        <div class="w-11/12 px-3 flex mx-auto justify-between items-center">
            <Link :href="route('welcome')">
                <img src="/images/logo.jpg" class="w-[85px]" />
            </Link>
            <nav class="navigation-items hidden md:block">
                <Link v-for="item of navItems" :href="item.href" class="mx-3" :class="item.class ?? ''">
                    <template v-if="item.type == 'normal'">
                        <span>{{ item.name }}</span>
                    </template>
                    <template v-else>
                        <i :class="item.name"></i>
                    </template>
                </Link>
            </nav>
            <i class="fa-solid fa-bars fa-xl cursor-pointer md:hidden" @click="toggleMenu"></i>
        </div>
        <nav class="navigation-items block md:hidden border-t">
            <Link v-for="item of navItems" :href="item.href" class="mx-3 block py-2 mb-1" :class="item.class ?? ''" v-if="isMenuVisible">
                <template v-if="item.type == 'normal'">
                    <span>{{ item.name }}</span>
                </template>
                <template v-else>
                    <i :class="item.name"></i> {{ item.text }}
                </template>
            </Link>
        </nav>
    </header>
</template>