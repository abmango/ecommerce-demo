<template>
  <GuestHeader />
  <div class="container mx-auto py-8 pt-24 max-w-lg">
    <h1 class="text-4xl font-bold mb-4">¡Orden realizada con éxito!</h1>
    <p class="text-xl text-gray-900 mb-6">
      Tu orden ha sido enviada a los vendedores. ¿Qué te gustaría hacer ahora?
    </p>
    <div class="flex justify-end gap-2">
      <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        @click="router.visit(`/orders/${orderId}`)">
        Ver detalles de la orden
      </button>
      <button
        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
        @click="router.visit('/orders')">
        Ver listado de pedidos
      </button>
    </div>
  </div>
</template>

<script setup>
import GuestHeader from '@/Components/GuestHeader.vue';
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()
const orderId = page.props.order_id || null
</script>
