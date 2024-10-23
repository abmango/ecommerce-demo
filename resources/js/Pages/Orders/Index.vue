<template>
  <div class="container mx-auto py-8">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Seguir comprando
      </InertiaLink>
    </div>
    <h1 class="text-2xl font-bold mb-6">Todas las Órdenes</h1>

    <div v-if="orders.length > 0" class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-300 table-auto">
        <thead>
          <tr>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">ID</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Usuario</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Correo</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Total</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Estado</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">{{ order.id }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ order.user?.name }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ order.user?.email }}</td>
            <td class="py-2 px-4 border border-gray-300">${{ order.total }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ order.status }}</td>
            <td class="py-2 px-4 border border-gray-300">
              <InertiaLink :href="`/orders/${order.id}`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ver Detalles
              </InertiaLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p class="text-center text-gray-500">No hay órdenes disponibles.</p>
    </div>
  </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  props: {
    orders: Array
  },
  components: { InertiaLink },
}
</script>
