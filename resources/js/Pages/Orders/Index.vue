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
            <!-- th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Correo</th -->
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Producto(s)</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Total a cobrar</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Estado</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Factura</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">{{ order.id }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ order.user?.name }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ productItemsCaption(order.items) }}</td>
            <!-- td class="py-2 px-4 border border-gray-300">{{ order.user?.email }}</td -->
            <td class="py-2 px-4 border border-gray-300">$ {{ order.total }}</td>
            <td class="py-2 px-4 border border-gray-300">
              <OrderStatusBadge :caption="ordersStatuses[order.status]" :status="order.status" />
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
                -
              </template>
            </td>

            <td class="py-2 px-4 border border-gray-300">
              <!-- InertiaLink :href="`/orders/${order.id}`"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ver Detalles
              </InertiaLink -->
              <a href="#" @click.prevent="openModal(order)">Ver</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p class="text-center text-gray-500">No hay órdenes disponibles.</p>
    </div>

    <DialogModal :show="modalVisible" @close="closeModal" :closeable="true">
      <template #title>
        Detalle del orden
      </template>

      <template #content>
        <h2 class="font-medium mb-2">Datos de contacto del cliente</h2>
        <table class="min-w-full bg-white border border-gray-300 table-auto">
          <tr class="border-b">
            <th class="py-2 px-4 border border-gray-300  bg-gray-200 w-40">Nombre completo</th>
            <td class="ps-2">{{ orderRecieved.user.name }}</td>
          </tr>
          <tr>
            <th class="py-2 px-4 border border-gray-300  bg-gray-200 w-40">Email de contacto</th>
            <td class="ps-2">{{ orderRecieved.user.email }}</td>
          </tr>
          <tr>
            <th class="py-2 px-4 border border-gray-300  bg-gray-200 w-40">CUIT</th>
            <td class="ps-2">{{ orderRecieved.user.cuit }}</td>
          </tr>
          <tr>
            <th class="py-2 px-4 border border-gray-300  bg-gray-200 w-40">Teléfono</th>
            <td class="ps-2">{{ orderRecieved.user.phone }}</td>
          </tr>
          <tr>
            <th class="py-2 px-4 border border-gray-300  bg-gray-200 w-40">Vía de preferencia</th>
            <td class="ps-2">{{ orderRecieved.user.preferred_contact_method }}</td>
          </tr>
        </table>
        <hr class="mt-5 mb-3">
        <h2 class="font-medium mb-1">Productos</h2>
        <table class="min-w-full bg-white border border-gray-300 table-auto">
          <thead>
            <tr>
              <td class="py-2 px-1 border border-gray-300 text-center bg-gray-200">#</td>
              <td class="py-2 px-1 border border-gray-300 text-center bg-gray-200">Nombre</td>
              <td class="py-2 px-1 border border-gray-300 text-center bg-gray-200">Cantidad</td>
              <td class="py-2 px-1 border border-gray-300 text-center bg-gray-200">Precio x unidad</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item of orderRecieved.items">
              <td class="py-2 px-1 border text-center">{{ item.id }}</td>
              <td class="py-2 px-1 border text-center">{{ item.product.name }}</td>
              <td class="py-2 px-1 border text-center">{{ item.quantity }}</td>
              <td class="py-2 px-1 border text-center">$ {{ item.product.price }}</td>
            </tr>
          </tbody>
        </table>
      </template>

      <template #footer>
        <div class="flex justify-between w-full">
          <div>
            <template v-if="(orderRecieved.status == 'pendiente')">
              <PrimaryButton @click="confirmOrder(orderRecieved.id)" class="mr-2">
                Aprobar
              </PrimaryButton>
              <DangerButton @click="rejectOrder(orderRecieved.id)" class="mr-2">
                Rechazar
              </DangerButton>
            </template>
          </div>
          <div>
            <SecondaryButton @click="closeModal">
              Cancelar
            </SecondaryButton>
          </div>
        </div>
      </template>
    </DialogModal>

  </div>
</template>

<script setup>
import DangerButton from '@/Components/DangerButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import GuestHeader from '@/Components/GuestHeader.vue'
import OrderStatusBadge from '@/Components/OrderStatusBadge.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { Link as InertiaLink, router } from '@inertiajs/vue3'
import { defineProps, ref } from 'vue'

const props = defineProps({
  orders: Array,
  ordersStatuses: Object 
})

const orderRecieved = ref()
const modalVisible = ref(false)
const closeModal = () => modalVisible.value = false
const openModal = (order) => {
  modalVisible.value = true
  orderRecieved.value = order
}

const productItemsCaption = (items) => {
  const total = items.length
  return total + ' ' + (total == 1 ? 'item' : 'items');
};

function confirmOrder(orderId) {
  router.post(`/orders/${orderId}/confirm`)
  closeModal()
}

function rejectOrder(orderId) {
  router.post(`/orders/${orderId}/reject`)
  closeModal()
}


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