<script setup>
import { Link as InertiaLink } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    products: Array
});

function restoreProduct(id) {
    if (confirm('¿Seguro que deseas restaurar este producto?')) {
        router.put(`/products/${id}/restore`, {}, {
            onSuccess: () => {
                alert('Producto restaurado correctamente.');
            },
            onError: (errors) => {
                console.error(errors);
                alert('Hubo un error al restaurar el producto.');
            }
        });
    }
}
</script>
<template>
    <div>
        <div class="flex justify-start">
            <InertiaLink href="/products/create"
                class="bg-lime-700 hover:bg-lime-900 text-white py-2 px-4 rounded block mb-2">
                Agregar nuevo
            </InertiaLink>
        </div>
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">ID</th>
                    <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Nombre</th>
                    <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Precio</th>
                    <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Estado</th>
                    <th class="py-2 px-4 border border-gray-300 text-left bg-gray-200">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in props.products" :key="product.id" class="text-center">
                    <td class="py-2 px-4 border border-gray-300">{{ product.id }}</td>
                    <td class="py-2 px-4 border border-gray-300">{{ product.name }}</td>
                    <td class="py-2 px-4 border border-gray-300">$ {{ product.price | currency }}</td>
                    <td class="py-2 px-4 border border-gray-300">
                        <span v-if="product.deleted_at" class="text-red-500 font-semibold">Dado de baja</span>
                        <span v-if="product.stock == 0" class="text-red-500 font-semibold">Sin stock</span>
                        <span v-if="!product.deleted_at && product.stock > 0"
                            class="text-green-500 font-semibold">Disponible</span>
                    </td>
                    <td class="py-2 px-4 border border-gray-300">
                        <InertiaLink v-if="!product.deleted_at" :href="`/products/${product.id}`"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                            Ver Detalles
                        </InertiaLink>
                        <button v-else @click="restoreProduct(product.id)"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                            Restaurar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>