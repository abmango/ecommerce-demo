<script setup>
import { Link as InertiaLink } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    products: Array,
    isLogged: Boolean
});


function addToCart(productId) {
  return new Promise((resolve, reject) => {
    router.post(`/cart/add/${productId}`, {}, {
      onSuccess: resolve,
      onError: reject
    });
  });
}

async function processPurchase(productId) {
  try {
    await addToCart(productId);
    console.log('Producto agregado al carrito, procediendo a la compra.');
    if (!props.isLogged.value) {
      alert('Debes iniciar sesión para realizar la compra.');
      router.visit('/login');
      return;
    }

    await router.post('/cart/checkout', {}, {
      onSuccess: () => {
        alert('¡Orden de compra realizada! La misma ha sido enviada a los vendedores.');
        router.visit('/orders');
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

</script>
<template>
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
            <div v-for="product in props.products" :key="product.id"
                :class="{ 'bg-white p-4 rounded-lg shadow': true, 'opacity-50': product.deleted_at || product.stock == 0 }">
                <InertiaLink :href="`/products/${product.id}`" class="block"
                    :class="{ 'pointer-events-none': product.deleted_at }">
                    <img :src="product.image ?? '/images/logo.jpg'" alt="Imagen del producto"
                        class="w-full h-48 object-cover mb-4" />
                </InertiaLink>
                <h2 class="first-letter:uppercase font-semibold text-lg">{{ product.name }}</h2>
                <p class="text-gray-500">$ {{ product.price }}</p>
                <!-- p v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</p -->
                <div class="flex justify-end items-center mt-3">
                    <template v-if="product.stock > 0">
                        <button @click="addToCart(product.id)"
                            class="border border-indigo-500 text-indigo-500 p-2 mx-1 rounded-md">
                            <i class="me-1 fas fa-cart-shopping"></i>
                            <span>Agregar</span>
                        </button>
                        <button @click="processPurchase(product.id)"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
                            <span>Ordenar</span>
                            <i class="fa-solid fa-circle-chevron-right ms-1"></i>
                        </button>
                    </template>
                    <template v-else>
                        <p v-if="product.stock == 0" class="text-red-500 font-semibold">Fuera de stock</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>