<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 hover:duration-150 p-2 border hover:border-indigo-500 hover:ring-indigo-500 outline-none rounded-md"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
