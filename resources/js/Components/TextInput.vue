<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    filterType: {
        type: String,
        default: 'none' // 'numeric', 'alphabetic', etc.
    },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

const handleInput = (event) => {
    let value = event.target.value;
    
    if (props.filterType === 'numeric') {
        value = value.replace(/\D/g, ''); 
        
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
    } else if (props.filterType === 'phone') {
        value = value.replace(/\D/g, ''); 
        
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
    }
    
    event.target.value = value;

    emit('update:modelValue', value);
};

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 hover:duration-150 p-2 border hover:border-indigo-500 hover:ring-indigo-500 outline-none rounded-md"
        :value="modelValue"
        @input="handleInput"
    >
</template>
