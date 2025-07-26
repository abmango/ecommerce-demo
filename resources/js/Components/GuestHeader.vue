<script setup>
import { Link } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import UserMenu from '@/Components/UserMenu.vue'

const isUserMenuVisible = ref(false);

const toggleUserMenu = () => {
    isUserMenuVisible.value = !isUserMenuVisible.value;
};

const page = usePage()

const auth = computed(() => page.props.auth || { user: null, role: null });

const isLoggedIn = computed(() => !!auth.value.user);
const isAdmin = computed(() => auth.value.user?.role === 'admin');

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
    const isOnWelcomePage = page.url.startsWith('/')

    const targetId = href.split('#')[1]
    const targetUrl = `/?scrollTo=${targetId}`

    if (isOnWelcomePage) {
        // Small scroll into the same page
        smoothScrollTo(`#${targetId}`)
    } else {
        // Redirect adding the parameter
        window.location.href = targetUrl
    }
}

function handleInicioClick() {
    if (window.location.pathname === '/' || window.location.pathname === '/welcome') {
        smoothScrollTo('body');
    } else {
        window.location.href = '/';
    }
}

function handleAnchorRedirect(href) {
    const targetId = href.replace('#', '');
    const isOnWelcomePage = window.location.pathname === '/' || window.location.pathname === '/welcome';

    if (isOnWelcomePage) {
        smoothScrollTo(`#${targetId}`);
    } else {
        window.location.href = `/?scrollTo=${targetId}`;
    }
}

const navItems = reactive([
    { type: 'inicio', name: 'Inicio' },
    { type: 'anchor', name: 'Nosotros', href: '#nosotros' },
    { type: 'anchor', name: 'Especialidades', href: '#especialidades' },
    { type: 'anchor', name: 'Contacto', href: '#contactanos' },
]);

// Siempre mostrar carrito
navItems.push({
    type: 'icon',
    name: 'fas fa-cart-shopping',
    href: route('cart.index'),
    class: 'text-indigo-500',
    text: 'Mi carrito'
});

if (isLoggedIn.value) {

    if (isAdmin.value) {
        // Quitar el carrito y poner Órdenes en su lugar
        const cartIndex = navItems.findIndex(item => item.href === route('cart.index'));
        if (cartIndex !== -1) navItems.splice(cartIndex, 1);

        navItems.push({
            type: 'normal',
            name: 'Órdenes',
            href: route('orders.index'),
            class: 'bg-red-600 text-white p-2 ms-1 rounded-sm hover:text-white hover:border-none w-auto'
        });
    }
} else {
    navItems.push({
        type: 'normal',
        name: 'Ingresar',
        href: route('login'),
        class: 'bg-indigo-500 text-white p-2 ms-1 rounded-sm hover:text-white hover:border-none w-auto'
    });

}

</script>

<template>
    <header class="w-full bg-white fixed shadow-sm top-0 start-0 z-30">
        <div class="w-11/12 px-3 flex mx-auto justify-between items-center">
            <Link :href="route('welcome')">
            <img src="/images/logo.jpg" class="w-[85px]" />
            </Link>
            <nav class="navigation-items hidden md:block">
                <div class="flex items-center space-x-3">


                    <!-- Resto de botones navItems -->
                    <template v-for="item in navItems" :key="item.name">
                        <a v-if="item.type === 'inicio'" href="javascript:void(0)" @click.prevent="handleInicioClick"
                            class="hover:underline cursor-pointer" :class="item.class ?? ''">
                            {{ item.name }}
                        </a>

                        <a v-else-if="item.type === 'anchor'" href="javascript:void(0)"
                            @click.prevent="() => handleAnchorRedirect(item.href)"
                            class="hover:underline cursor-pointer" :class="item.class ?? ''">
                            {{ item.name }}
                        </a>

                        <Link v-else :href="item.href" class="" :class="item.class ?? ''"
                            @click="isUserMenuVisible = false">
                        <template v-if="item.type === 'normal'">
                            <span>{{ item.name }}</span>
                        </template>
                        <template v-else>
                            <i :class="item.name"></i> {{ item.text }}
                        </template>
                        </Link>
                    </template>
                    <!-- Dashboard con UserMenu -->
                    <UserMenu v-if=isLoggedIn align="right">
                        <template #trigger>
                            <button
                                class="bg-green-600 text-white p-2 rounded-sm hover:text-white hover:border-none w-auto">
                                Dashboard
                            </button>
                        </template>
                    </UserMenu>
                </div>


            </nav>
            <i class="fa-solid fa-bars fa-xl cursor-pointer md:hidden" @click="toggleUserMenu"></i>
        </div>

        <!-- Burger menu -->
        <nav class="navigation-items block md:hidden border-t" :class="{ 'hidden': !isUserMenuVisible }">
            <template v-for="item in navItems" :key="item.name">
                <a v-if="item.type === 'inicio'" href="javascript:void(0)" @click.prevent="handleInicioClick"
                    class="mx-3 block py-2 mb-1" :class="item.class ?? ''">
                    {{ item.name }}
                </a>

                <a v-else-if="item.type === 'anchor'" href="javascript:void(0)"
                    @click.prevent="() => handleAnchorClick(item.href)" class="mx-3 block py-2 mb-1"
                    :class="item.class ?? ''">
                    {{ item.name }}
                </a>

                <Link v-else :href="item.href" class="mx-3 block py-2 mb-1" :class="item.class ?? ''"
                    @click="isUserMenuVisible = false">
                <template v-if="item.type === 'normal'">
                    <span>{{ item.name }}</span>
                </template>
                <template v-else>
                    <i :class="item.name"></i> {{ item.text }}
                </template>
                </Link>
            </template>
                <UserMenu v-if=isLoggedIn align="left">
                    <template #trigger>
                        <button
                            class="bg-green-600 text-white p-2 rounded-sm hover:text-white hover:border-none w-auto">
                            Dashboard
                        </button>
                    </template>
                </UserMenu>
        </nav>
    </header>
</template>
