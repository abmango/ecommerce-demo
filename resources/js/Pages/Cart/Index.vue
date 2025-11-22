<template>
  <GuestHeader />

  <Head title="Carrito"></Head>
  <div class="container mx-auto bg-white p-6 rounded shadow mt-32">
    <h1 class="text-2xl font-bold mb-6 text-center">Carrito de Compras</h1>

    <div v-if="cart.length > 0" class="overflow-x-auto relative">
      <!-- Overwrite table while isCartBeingUpdated is in true -->
      <div class="absolute top-0 start-0 bg-white opacity-50 w-full h-full" v-if="isCartBeingUpdated"></div>

      <table class="min-w-full bg-white border border-gray-300 table-auto">
        <thead>
          <tr>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Producto</th>
            <!-- th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Cantidad</th -->
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Precio x unidad</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Cantidad</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in cart" :key="item.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">
              <div class="flex items-top">
                <img :src="item.image" alt="Imagen del producto" class="w-16 h-16 object-cover mr-4" />
                <div class="text-start">
                  <h3 class="font-semibold text-lg">{{ item.name }}</h3>
                  <p>
                    <b class="underline text-sm text-red-500 cursor-pointer"
                      @click="removeFromCart(item.id)">Eliminar</b>
                  </p>
                </div>
              </div>
            </td>
            <!-- td class="py-2 px-4 border border-gray-300">{{ item.quantity }}</td -->
            <td class="py-2 px-4 border border-gray-300">${{ item.price }}</td>
            <td class="py-2 border-0 border-gray-300">
              <div class="flex justify-center gap-2">
                <div class="flex justify-center">
                  <SecondaryButton class="bg-slate-100" @click="decreaseQuantity(item)" :disabled="item.quantity <= 1">-
                  </SecondaryButton>
                  <TextInput v-model="item.quantity" @input="updateQuantity(item)" class="mx-2" />
                  <SecondaryButton class="bg-slate-100" @click="increaseQuantity(item)">+</SecondaryButton>
                </div>
              </div>
            </td>
            <td class="py-2 px-4 border border-gray-300">${{ (item.price * item.quantity).toFixed(2) }}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="py-2 px-4 border border-gray-300 font-bold" style="text-align: end;">Total: ${{
              total.toFixed(2) }}</td>
          </tr>
        </tfoot>
      </table>


      <!-- Botón para procesar la compra -->
      <div class="mt-4 text-right">

      </div>
    </div>
    <div v-else>
      <p class="text-center text-gray-500">El carrito está vacío.</p>

    </div>
    <CartActionButtons :is-empty="cart.length === 0" :proceedToCheckoutUrl="processPurchase" />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Head, router, Link as InertiaLink } from '@inertiajs/vue3';
import GuestHeader from '@/Components/GuestHeader.vue';
import CartActionButtons from '@/Components/Cart/ActionButtons.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { debounce } from 'lodash';

// Props
const props = defineProps({
  cart: Array,
  auth: Object,
});


const isCartBeingUpdated = ref(false);

// Computed
const total = computed(() => {
  return props.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
});


function increaseQuantity(item) {
  item.quantity++;
  updateQuantity(item);
}

function decreaseQuantity(item) {
  if (item.quantity > 1) {
    item.quantity--;
    updateQuantity(item);
  }
}

const updateQuantity = debounce(updateQuantityOnSession, 750);

// Methods
function updateQuantityOnSession(item) {

  const { id, quantity } = item;

  if (quantity < 1) {
    alert('La cantidad debe ser al menos 1');
    router.reload();
    return;
  }
  console.log('Actualizando producto con ID:', id, 'Cantidad:', quantity);

  isCartBeingUpdated.value = true;

  router.post(`/cart/update/${id}`, { quantity },
    {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        console.log('Cantidad actualizada correctamente');
      },
      onError: (errors) => {
        if (errors.quantity) {
          alert(errors.quantity);
        }
      },
      onFinish: () => {
        isCartBeingUpdated.value = false;
      }
    }
  );
}

function removeFromCart(id) {
  router.delete(`/cart/remove/${id}`)
    .then(response => {
      if (response.data.success) {
        router.reload();
      } else {
        console.error('Error al eliminar el producto:', response.data.message);
      }
    })
    .catch(error => {
      console.error('Error en la petición:', error);
    });
}

function processPurchase() {
  if (!props.auth || !props.auth.user) {
    alert('Debes iniciar sesión para realizar la compra.');
    router.visit('/login');
    return;
  }
  router.post('/cart/checkout', {}, {
    onSuccess: () => {
      alert('¡Orden de compra realizada! La misma ha sido enviada a los vendedores.');
      router.visit('/orders');
    },
    onError: (errors) => {
      console.error(errors);
      alert('Error al procesar la orden.');
    }
  });
}

</script>

<style scoped>
/* Style for tables */
table {
  border-collapse: collapse;
  width: 100%;
}

/* Style for cells */
th,
td {
  text-align: center;
  padding: 8px;
  border: 1px solid #ddd;
}

/* Style for headers */
thead th {
  background-color: #f2f2f2;
  font-weight: bold;
}

/* Background for even cells */
tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Style for hover of rows */
tbody tr:hover {
  background-color: #f1f1f1;
}

/* Style for buttons */
button {
  transition: background-color 0.3s ease;
}
</style>
