<script setup>
import { Link } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

function smoothScrollTo(targetEl, duration = 800) {
    const target = document.querySelector(targetEl)
    if (!target) return

    const headerOffset = 90
    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerOffset
    const startPosition = window.pageYOffset
    const distance = targetPosition - startPosition
    let startTime = null

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime
        const timeElapsed = currentTime - startTime
        const run = easeInOutQuad(timeElapsed, startPosition, distance, duration)
        window.scrollTo(0, run)
        if (timeElapsed < duration) requestAnimationFrame(animation)
    }

    function easeInOutQuad(t, b, c, d) {
        t /= d / 2
        if (t < 1) return c / 2 * t * t + b
        t--
        return -c / 2 * (t * (t - 2) - 1) + b
    }

    requestAnimationFrame(animation)
}

function handleAnchorClick(href) {
    const isOnWelcomePage = page.url === '/'

    if (isOnWelcomePage) {
        smoothScrollTo(href)
    } else {
        window.location.href = href
    }
}

function handleInicioClick() {
    if (page.url === '/') {
        smoothScrollTo('body');
    } else {
        window.location.href = '/';
    }
}


const navItems = reactive([
    { type: 'inicio', name: 'Inicio' },
    { type: 'anchor', name: 'Nosotros', href: '#nosotros' },
    { type: 'anchor', name: 'Especialidades', href: '#especialidades' },
    { type: 'anchor', name: 'Contacto', href: '#contactanos' },
    {
        type: 'normal',
        name: 'Ingresar',
        href: route('login'),
        class: 'bg-indigo-500 text-white p-2 ms-1 rounded-sm hover:text-white hover:border-none w-auto'
    },
    {
        type: 'icon',
        name: 'fas fa-cart-shopping',
        href: route('cart.index'),
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
                <template v-for="item of navItems">
                    <!-- Botón Inicio -->
                    <a v-if="item.type === 'inicio'" @click="handleInicioClick"
                        class="mx-3 hover:underline cursor-pointer">
                        {{ item.name }}
                    </a>

                    <!-- Anclas internas -->
                    <a v-else-if="item.type === 'anchor'" :href="item.href" class="mx-3 hover:underline cursor-pointer"
                        :class="item.class ?? ''" @click.prevent="() => handleAnchorClick(item.href)">
                        {{ item.name }}
                    </a>

                    <!-- Resto de enlaces con <Link> -->
                    <Link v-else :href="item.href" class="mx-3" :class="item.class ?? ''"
                        @click="isMenuVisible = false">
                    <template v-if="item.type === 'normal'">
                        <span>{{ item.name }}</span>
                    </template>
                    <template v-else>
                        <i :class="item.name"></i> {{ item.text }}
                    </template>
                    </Link>
                </template>

            </nav>
            <i class="fa-solid fa-bars fa-xl cursor-pointer md:hidden" @click="toggleMenu"></i>
        </div>
        <nav class="navigation-items block md:hidden border-t">
            <Link v-for="item of navItems" :href="item.href" class="mx-3 block py-2 mb-1" :class="item.class ?? ''"
                v-if="isMenuVisible">
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