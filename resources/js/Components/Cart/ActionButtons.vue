<script setup>
import { Link as InertiaLink } from '@inertiajs/vue3';

const props = defineProps({
isEmpty: {
        type: Boolean,
        default: true
    },
    proceedToCheckoutUrl: {
        type: Function,
        required: true
    },
});
</script>
<template>
    <div>
        <div class="flex justify-end gap-2">
            <InertiaLink href="/products" class="bg-green-500 hover:bg-green-700 text-white py-2 px-2 rounded">
                Seguir comprando
            </InertiaLink>
            <button v-if="!props.isEmpty" @click="props.proceedToCheckoutUrl()" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Finalizar compra 
            </button>
        </div>        
    </div>
</template>