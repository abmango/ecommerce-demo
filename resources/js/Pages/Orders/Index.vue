<template>
  <GuestHeader />
  <div class="container mx-auto py-8 pt-24">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Volver a productos
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
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Factura</th>
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
              <InertiaLink :href="`/orders/${order.id}`"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ver Detalles
              </InertiaLink>
            </td>
            <td class="py-2 px-4 border border-gray-300">
              <template v-if="order.invoice_path">
                <a :href="`/storage/${order.invoice_path}`" target="_blank" class="text-blue-600 hover:underline">
                  Ver Factura
                </a>
              </template>
              <template v-else-if="order.status === 'confirmada'">
                <input type="file" accept="application/pdf" @change="uploadInvoice(order.id, $event)"
                  class="block w-full text-sm text-gray-600" />
              </template>
              <template v-else>
                Pendiente
              </template>
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

<script setup>
import GuestHeader from '@/Components/GuestHeader.vue'
import { Link as InertiaLink, router } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  orders: Array
})

function uploadInvoice(orderId, event) {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('invoice', file)

  router.post(`/orders/${orderId}/invoice`, formData, {
    forceFormData: true,
    onSuccess: () => {
      router.reload({ only: ['orders'] })
    }
  })
}
</script>