<template>
  <div class="container mx-auto py-8">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <Link href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
      Volver
      </Link>
    </div>

    <h1 class="text-2xl font-bold mb-6">{{ product.name }}</h1>
    <img :src="product.image" alt="Imagen del producto" class="w-64 h-64 object-cover mb-4">
    <p class="text-gray-500">{{ product.description }}</p>
    <p class="text-lg font-bold">Precio: $ {{ product.price }}</p>
    <p class="text-gray-500">Stock: {{ product.stock }}</p>
    <p class="text-gray-500">Tipo: {{ product.type }}</p>

    <!-- Botón para agregar al carrito -->
    
      <button v-if="auth && auth.role === 'user'"@click="addToCart"
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Agregar al carrito
      </button>

    <div v-if="auth && auth.role === 'admin'" class="flex space-x-4 mt-4">
      <button @click="deleteProduct"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Dar de baja
      </button>
      <Link :href="`/products/${product.id}/edit`"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
      Editar
      </Link>
    </div>

  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import DeleteProduct from './Delete.vue';

export default {
  props: {
    product: Object,
    auth: Object,
  },
  components: {
    Link,
    DeleteProduct,
  },
  methods: {
    deleteProduct() {
      if (confirm('¿Estás seguro de que quieres dar de baja este producto?')) {
        this.$inertia.delete(`/products/${this.product.id}`)
          .then(() => {
            this.$inertia.visit('/products'); // Redirige al listado después de dar de baja
          });
      }
    },
    addToCart() {
      this.$inertia.post(`/cart/add/${this.product.id}`);
    },
  },
};
</script>


<style scoped>
/* Estilos opcionales */
.btn {
  padding: 4px 8px;
  background-color: #4f46e5;
  /* Cambia el color según tu diseño */
  color: rgb(255, 255, 255);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn:hover {
  background-color: #4338ca;
  /* Cambia el color en hover */
}
</style>