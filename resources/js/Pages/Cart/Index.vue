<template>
  <div class="container mx-auto py-8">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ver más productos
      </InertiaLink>
    </div>

    <h1 class="text-2xl font-bold mb-6 text-center">Carrito de Compras</h1>

    <div v-if="cart.length > 0" class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-300 table-auto">
        <thead>
          <tr>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Producto</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Cantidad</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Precio</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Subtotal</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in cart" :key="item.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">{{ item.name }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ item.quantity }}</td>
            <td class="py-2 px-4 border border-gray-300">${{ item.price }}</td>
            <td class="py-2 px-4 border border-gray-300">${{ (item.price * item.quantity).toFixed(2) }}</td>
            <td class="py-2 px-4 border border-gray-300">
              <input type="number" v-model="item.quantity" @change="updateQuantity(item.id, item.quantity)">
              <button @click="removeFromCart(item.id)"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Total -->
      <div class="mt-4 text-right">
        <p class="text-xl font-bold">Total: ${{ total.toFixed(2) }}</p>
      </div>

      <!-- Botón para procesar la compra -->
      <div class="mt-4 text-right">
        <button @click="processPurchase" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Comprar
        </button>
      </div>
    </div>
    <div v-else>
      <p class="text-center text-gray-500">El carrito está vacío.</p>
    </div>
  </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';
import axios from 'axios';

export default {
  props: {
    cart: Array,
  },
  computed: {
    total() {
      return this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    }
  },

  components: { InertiaLink },
  methods: {
    updateQuantity(id, quantity) {
      if (quantity < 1) {
        console.error('La cantidad debe ser al menos 1');
        return; // Salir si la cantidad es inválida
      }
      console.log('Actualizando producto con ID:', id, 'Cantidad:', quantity); // Para depuración
      axios.post(`/cart/update/${id}`, { quantity: quantity })
        .then(response => {
          console.log('Update response:', response);
          if (response.data.success) {
            this.$inertia.reload(); // Recargar la página si se actualizó correctamente
          } 
          else {
            console.error('Error al actualizar la cantidad:', response.data.message);
          }
        })
        .catch(error => {
          if (error.response) {
            console.error('Error en la petición:', error.response.data);
          } else if (error.request) {
            console.error('No se recibió respuesta del servidor:', error.request);
          } else {
            console.error('Error al configurar la petición:', error.message);
          }
        });
    },

    removeFromCart(id) {
      axios.delete(`/cart/remove/${id}`)
        .then(response => {
          if (response.data.success) {
            this.$inertia.reload(); // Recargar la página si se eliminó correctamente
          } else {
            console.error('Error al eliminar el producto:', response.data.message);
          }
        })
        .catch(error => {
          console.error('Error en la petición:', error);
        });
    },

    processPurchase() {
      axios.post('/cart/checkout')
        .then(response => {
          this.$inertia.visit('/orders');
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
}
</script>

<style scoped>
/* Estilo para las tablas */
table {
  border-collapse: collapse;
  width: 100%;
}

/* Estilo para las celdas de la tabla */
th,
td {
  text-align: center;
  padding: 8px;
  border: 1px solid #ddd;
}

/* Estilo para los encabezados de la tabla */
thead th {
  background-color: #f2f2f2;
  font-weight: bold;
}

/* Fondo diferente para filas pares */
tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Estilo para el hover de las filas */
tbody tr:hover {
  background-color: #f1f1f1;
}

/* Estilo para los botones */
button {
  transition: background-color 0.3s ease;
}
</style>
