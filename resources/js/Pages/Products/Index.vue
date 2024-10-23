<template>
  <div class="container mx-auto py-8">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/cart" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Carrito
      </InertiaLink>
    </div>

    <div v-if="auth && auth.role === 'admin'" class="absolute top-0 left-0 mt-4 mr-4">
      <InertiaLink href="/products/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Agregar producto
      </InertiaLink>
    </div>

    <h1 class="text-2xl font-bold mb-6">Lista de Productos</h1>

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
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div 
          v-for="product in products" 
          :key="product.id" 
          :class="{'bg-white p-4 rounded-lg shadow': true, 'opacity-50': product.deleted_at || product.stock == 0}" 
        > 
          <InertiaLink :href="`/products/${product.id}`" class="block" :class="{'pointer-events-none': product.deleted_at}"> 
            <img :src="product.image" alt="Imagen del producto" class="w-full h-48 object-cover mb-4" />
          </InertiaLink>
          <h2 class="font-bold text-lg">{{ product.name }}</h2>
          <p class="text-gray-500">$ {{ product.price | currency }}</p>
          <p v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</p>
          <p v-if="product.stock == 0" class="text-red-500 font-semibold">Sin stock</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  props: {
    products: Array,
    auth: Object,
  },
  components: { InertiaLink },
  methods: {
    removeProduct(productId) {
      this.products = this.products.filter(product => product.id !== productId);
    },
  },
};
</script>
