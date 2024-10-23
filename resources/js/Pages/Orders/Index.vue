<template>
    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">Tus Órdenes</h1>
  
      <div v-if="orders.length > 0" class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 table-auto">
          <thead>
            <tr>
              <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">ID</th>
              <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Total</th>
              <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Estado</th>
              <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in orders" :key="order.id" class="text-center">
              <td class="py-2 px-4 border border-gray-300">{{ order.id }}</td>
              <td class="py-2 px-4 border border-gray-300">${{ order.total }}</td>
              <td class="py-2 px-4 border border-gray-300">{{ order.status }}</td>
              <td class="py-2 px-4 border border-gray-300">
                <inertia-link :href="`/orders/${order.id}`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Ver Detalles
                </inertia-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p class="text-center text-gray-500">No tienes órdenes aún.</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      orders: Array
    }
  }
  </script>
  