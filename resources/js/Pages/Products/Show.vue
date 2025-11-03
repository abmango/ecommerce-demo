<template>
  <Head title="Detalle de producto" />
  <GuestHeader />

  <div class="container mx-auto mt-24 px-4">
    <Link
      href="/products"
      class="inline-flex items-center mb-6 text-indigo-600 hover:text-indigo-800 font-medium"
    >
      <i class="fa-solid fa-arrow-left mr-2"></i> Volver a productos
    </Link>

    <div
      class="bg-white rounded-2xl shadow-lg p-8 flex flex-col lg:flex-row gap-10"
    >
      <!-- Imagen -->
      <div class="flex-1 flex justify-center items-center">
        <img
          :src="product.image ?? '/images/logo.jpg'"
          alt="Imagen del producto"
          class="rounded-xl max-h-[450px] object-contain shadow-sm"
        />
      </div>

      <!-- Detalles -->
      <div class="flex-1 flex flex-col justify-between">
        <div>
          <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
          <p class="whitespace-pre-line text-gray-600 leading-relaxed mb-6">
            {{ product.description }}
          </p>

          <div class="space-y-2 mb-6">
            <p class="text-2xl font-semibold text-green-600">
              $ {{ product.price }}
            </p>
            <p class="text-sm text-gray-500">
              <span class="font-medium">Stock:</span> {{ product.stock }}
            </p>
            <p class="text-sm text-gray-500">
              <span class="font-medium">Categoría:</span> {{ product.type }}
            </p>
          </div>
        </div>

        <!-- Botones -->
        <div v-if="!isAdmin" class="flex flex-col sm:flex-row gap-4 mt-6">
          <button
            @click="addToCart()"
            class="flex items-center justify-center gap-2 bg-indigo-500 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition"
          >
            <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
          </button>

          <button
            @click="processPurchase()"
            class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition"
          >
            <i class="fa-solid fa-bag-shopping"></i> Comprar ahora
          </button>
        </div>

        <div v-if="isAdmin" class="flex flex-col sm:flex-row gap-4 mt-6">
          <Link
            :href="`/products/${product.id}/edit`"
            class="flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition"
          >
            <i class="fa-solid fa-pen-to-square"></i> Editar producto
          </Link>

          <button
            @click="deleteProduct"
            class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition"
          >
            <i class="fa-solid fa-trash-can"></i> Dar de baja
          </button>
        </div>
      </div>
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
        this.$inertia.post(`/cart/add/${this.product.id}`, {}, {
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