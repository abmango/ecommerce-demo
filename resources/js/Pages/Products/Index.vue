<template>
  <GuestHeader />
  <Head title="Catalogo de productos"></Head>
  <div class="container mx-auto py-8 mt-20">
    <div v-if="!auth || (auth && auth.role === 'user')" class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/cart" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Carrito
      </InertiaLink>
    </div>

    <div v-if="auth && auth.role === 'admin'" class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/orders" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Órdenes de compra
      </InertiaLink>
    </div>

    <div v-if="auth && auth.role === 'admin'" class="absolute top-0 left-0 mt-4 mr-4">
      <InertiaLink href="/products/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Agregar producto
      </InertiaLink>
    </div>

    <h1 class="text-2xl border-b pb-2 font-semibold mb-6">Lista de Productos</h1>

    <!-- Vista para Administradores -->
    <div v-if="auth && auth.role === 'admin'">
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
              <span v-if="!product.deleted_at && product.stock > 0" class="text-green-500 font-semibold">Disponible</span>
            </td>
            <td class="py-2 px-4 border border-gray-300">
              <InertiaLink :href="`/products/${product.id}`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
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
        <div 
          v-for="product in products" 
          :key="product.id" 
          :class="{'bg-white p-4 rounded-lg shadow': true, 'opacity-50': product.deleted_at || product.stock == 0}" 
        > 
          <InertiaLink :href="`/products/${product.id}`" class="block" :class="{'pointer-events-none': product.deleted_at}"> 
            <!-- img :src="product.image ?? '/images/logo.jpg'" alt="Imagen del producto" class="w-full h-48 object-cover mb-4" / -->
            <div class="w-full bg-contain bg-no-repeat bg-center h-28" style="background-image: url('/images/logo.jpg');"></div>
          </InertiaLink>
          <h2 class="first-letter:uppercase font-semibold text-lg">{{ product.name }}</h2>
          <p class="text-gray-500">$ {{ product.price | currency }}</p>
          <!-- p v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</p -->         
          <div class="flex justify-end items-center mt-3">
            <template v-if="product.stock > 0">
              <InertiaLink :href="`/products/${product.id}`" class="border border-indigo-500 text-indigo-500 p-2 mx-1 rounded-md">
                <i class="me-1 fas fa-cart-shopping"></i>
                <span>Agregar</span>
              </InertiaLink>
              <InertiaLink :href="`/products/${product.id}`" class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
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

const props = defineProps({
  products: Array,
  auth: Object,
  isAdmin: Boolean,
})

</script>
