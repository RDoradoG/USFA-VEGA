<script setup>
import Form from './Form.vue';

defineProps([
  'sedes',
  'carreras',
  'horarios',
  'estados',
  'usuarios',
  'fuentes',
  'codigos_pais',
  'promociones'
]);
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl mb-4">Nuevo Lead</h1>
    <Form v-bind="$props" />
  </div>
</template>