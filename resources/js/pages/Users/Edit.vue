<script setup>
import Form from './Form.vue';

const props = defineProps({
  usuario: Object
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Editar Usuario</h1>
    <Form :usuario="usuario" />
  </div>
</template>