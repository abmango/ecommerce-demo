<template>
  <GuestHeader />

  <Head title="Catalogo de productos"></Head>
  <div class="container mx-auto p-5 mt-20">

    <div class="catalogo-header flex justify-between items-center border-b border-b-slate-300 mb-3">
      <h1 class="text-2xl font-semibold">Lista de Productos</h1>
      <div v-if="isAdmin">
        <InertiaLink href="/products/create"
          class="bg-lime-700 hover:bg-lime-900 text-white py-2 px-4 rounded block mb-2">
          Agregar nuevo
        </InertiaLink>
      </div>
    </div>

    <!-- Vista para Administradores -->
    <div v-if="isAdmin">

      <table class="min-w-full border border-gray-300">
        <thead>
          <tr>
            <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">ID</th>
            <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Nombre</th>
            <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Precio</th>
            <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Estado</th>
            <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">{{ product.id }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ product.name }}</td>
            <td class="py-2 px-4 border border-gray-300">$ {{ product.price | currency }}</td>
            <td class="py-2 px-4 border border-gray-300">
              <span v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</span>
              <span v-if="product.stock == 0" class="text-red-500 font-semibold">Sin stock</span>
              <span v-if="!product.deleted_at && product.stock > 0"
                class="text-green-500 font-semibold">Disponible</span>
            </td>
            <td class="py-2 px-4 border border-gray-300">
              <InertiaLink :href="`/products/${product.id}`"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                Ver Detalles
              </InertiaLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Vista para Usuarios -->
    <div v-else>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
        <div v-for="product in products" :key="product.id"
          :class="{'bg-white p-4 rounded-lg shadow': true, 'opacity-50': product.deleted_at || product.stock == 0}">
          <InertiaLink :href="`/products/${product.id}`" class="block"
            :class="{'pointer-events-none': product.deleted_at}">
            <!-- img :src="product.image ?? '/images/logo.jpg'" alt="Imagen del producto" class="w-full h-48 object-cover mb-4" / -->
            <div class="w-full bg-contain bg-no-repeat bg-center h-28"
              style="background-image: url('/images/logo.jpg');"></div>
          </InertiaLink>
          <h2 class="first-letter:uppercase font-semibold text-lg">{{ product.name }}</h2>
          <p class="text-gray-500">$ {{ product.price }}</p>
          <!-- p v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</p -->
          <div class="flex justify-end items-center mt-3">
            <template v-if="product.stock > 0">
              <button @click="addToCart(product.id)" class="border border-indigo-500 text-indigo-500 p-2 mx-1 rounded-md">
                <i class="me-1 fas fa-cart-shopping"></i>
                <span>Agregar</span>
              </button>
              <InertiaLink :href="`/products/${product.id}`"
                class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
                <span>Ordenar</span>
                <i class="fa-solid fa-circle-chevron-right ms-1"></i>
              </InertiaLink>
            </template>
            <template v-else>
              <p v-if="product.stock == 0" class="text-red-500 font-semibold">Fuera de stock</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import GuestHeader from '@/Components/GuestHeader.vue';
import { Head, Link as InertiaLink } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  products: Array,
  auth: {
    type: Object,
    default: () => ({ user: null })
  },
})

const isLogged = computed(() => props.auth.user != null)
const isAdmin = computed(() => isLogged && props.auth?.user?.role === 'admin')

const addToCart = (productId) => {
  router.post(`/cart/add/${productId}`)
}

</script>
