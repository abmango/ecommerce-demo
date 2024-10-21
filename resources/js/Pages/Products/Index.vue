<template>
  <div class="container mx-auto py-8">

    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/cart" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ver Carrito
      </InertiaLink>
    </div>

    <h1 class="text-2xl font-bold mb-6">Lista de Productos</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div 
        v-for="product in products" 
        :key="product.id" 
        :class="{'bg-white p-4 rounded-lg shadow': true, 'opacity-50': product.deleted_at || product.stock==0}" 
      > <!-- Atenuar si está dado de baja -->
        <InertiaLink :href="`/products/${product.id}`" class="block" :class="{'pointer-events-none': product.deleted_at}"> <!-- Deshabilitar el enlace si está dado de baja -->
          <img :src="product.image" alt="Imagen del producto" class="w-full h-48 object-cover mb-4" />
        </InertiaLink>
        <h2 class="font-bold text-lg">{{ product.name }}</h2>
        <p class="text-gray-500">$ {{ product.price | currency }}</p>

        <!-- Mostrar un mensaje o etiqueta si está dado de baja -->
        <p v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</p>

        <p v-if="product.stock==0" class="text-red-500 font-semibold">Sin stock</p>
      </div>
    </div>
  </div>
</template>


<script>

import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  props: {
    products: Array,
  },
  components: { InertiaLink, },
  methods: {
    removeProduct(productId) {
      this.products = this.products.filter(product => product.id !== productId);
    },
  },
};
</script>