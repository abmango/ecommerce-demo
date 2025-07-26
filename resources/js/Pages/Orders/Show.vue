<template>
  <GuestHeader />
  <div class="container mx-auto pt-20 px-4">
    <div class="flex justify-end mb-6">
      <InertiaLink href="/orders" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Volver a las órdenes
      </InertiaLink>
    </div>
    
    <h1 class="text-2xl font-bold mb-6">Detalles de la Orden #{{ order.id }}</h1>

    <p class="mb-4"><strong>Total:</strong> ${{ order.total }}</p>
    <p class="mb-4"><strong>Estado:</strong> {{ order.status }}</p>

    <h3 class="text-xl font-bold mb-4">Productos</h3>
    <div v-if="order.items.length > 0">
      <ul>
        <li v-for="item in order.items" :key="item.id">
          {{ item.product.name }} - Cantidad: {{ item.quantity }} - Precio: ${{ item.price }}
        </li>
      </ul>
    </div>

    <!-- Sólo para los administradores -->
    <div v-if="$page.props.auth?.user?.role === 'admin'">
      <!-- Formulario para confirmar la orden -->
      <div v-if="order.status === 'pendiente'" class="flex space-x-4 mt-4">
        <form @submit.prevent="confirmOrder" method="POST">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Confirmar
          </button>
        </form>

        <!-- Formulario para rechazar la orden -->
        <form @submit.prevent="rejectOrder" method="POST">
          <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Rechazar
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import GuestHeader from '@/Components/GuestHeader.vue';
import { defineProps } from 'vue';
import { Link as InertiaLink } from '@inertiajs/vue3';


const props = defineProps({
  order: Object
});

function confirmOrder() {
  router.post(`/orders/${props.order.id}/confirm`);
}

function rejectOrder() {
  router.post(`/orders/${props.order.id}/reject`);
}
</script>