<template>

  <Head title="Detalle de producto"></Head>
  <GuestHeader></GuestHeader>
  <div class="container mx-auto py-8 mt-20">
    <div class="flex justify-between items-center mb-6">
      <Link href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
      Volver
      </Link>
    </div>

    <h1 class="text-2xl font-bold mb-6">{{ product.name }}</h1>
    <img :src="product.image ?? '/images/logo.jpg'" alt="Imagen del producto" class="w-64 h-64 object-cover mb-4">
    <p class="whitespace-pre-line text-gray-500">{{ product.description }}</p>
    <p class="text-lg font-bold">Precio: $ {{ product.price }}</p>
    <p class="text-gray-500">Stock: {{ product.stock }}</p>
    <p class="text-gray-500">Tipo: {{ product.type }}</p>

    <!-- Botón para agregar al carrito -->

    <div v-if="!isAdmin" class="flex space-x-4 mt-4">
      <button @click="addToCart"
        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        <i class="me-1 fas fa-cart-shopping"></i>
        <span>Agregar</span>
      </button>

      <button @click="processPurchase(product.id)"
        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        <span>Ordenar</span>
        <i class="fa-solid fa-circle-chevron-right ms-1"></i>
      </button>
    </div>

    <div v-if="isAdmin" class="flex space-x-4 mt-4">
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
import { Head, Link } from '@inertiajs/inertia-vue3';
import DeleteProduct from './Delete.vue';
import GuestHeader from '@/Components/GuestHeader.vue';

export default {
  props: {
    product: Object,
    auth: Object,
  },
  computed: {
    isLogged() {
      return this.auth.user != null
    },
    isAdmin() {
      return this.isLogged && this.auth.user.role === 'admin'
    },
  },
  components: {
    Link,
    DeleteProduct,
    Head,
    GuestHeader
  },
  methods: {
    deleteProduct() {
      if (confirm('¿Estás seguro de que quieres dar de baja este producto?')) {
        this.$inertia.delete(`/products/${this.product.id}`)
          .then(() => {
            this.$inertia.visit('/products');
          });
      }
    },
    addToCart() {
      return new Promise((resolve, reject) => {
        this.$inertia.post(`/cart/add/${productId}`, {}, {
          onSuccess: resolve,
          onError: reject
        });
      });
    },
    async processPurchase() {
      try {
        await this.addToCart();
        console.log('Producto agregado al carrito, procediendo a la compra.');

        if (!this.isLogged) {
          alert('Debes iniciar sesión para realizar la compra.');
          this.$inertia.visit('/login');
          return;
        }

        await this.$inertia.post('/cart/checkout', {}, {
          onSuccess: () => {
            alert('¡Orden de compra realizada! La misma ha sido enviada a los vendedores.');
            this.$inertia.visit('/orders');
          },
          onError: (errors) => {
            console.error(errors);
            alert('Error al procesar la orden.');
          }
        });
      } catch (e) {
        console.error(e);
        alert('Ocurrió un error al agregar el producto al carrito.');
      }
    }
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