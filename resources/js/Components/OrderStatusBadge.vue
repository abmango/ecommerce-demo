<template>
    <div class="p-1 border rounded-md" :class="statusStyle">
        {{ caption }}
    </div>
</template>
<script setup>
import { computed } from 'vue';

const props = defineProps({
    caption : String,
    status : String
})

const statusStyle = computed( () => {
    if(props.status == 'pendiente')
        return 'text-sky-700 bg-sky-200'
    else if(props.status == 'confirmada')
        return 'text-green-700 bg-green-200'    
    else
        return 'text-red-700 bg-red-200'
})
</script>