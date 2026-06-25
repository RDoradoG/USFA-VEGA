<script setup>
import Form from './Form.vue';

const props = defineProps({
  promo: Object
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Editar Promoción</h1>
    <Form :promo="promo" />
  </div>
</template>