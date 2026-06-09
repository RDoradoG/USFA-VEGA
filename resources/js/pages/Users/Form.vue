<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  usuario: Object
});

const form = useForm({
  nombre: props.usuario?.nombre || '',
  email: props.usuario?.email || '',
  password: '',
  rol: props.usuario?.rol || 'ASESOR'
});

const submit = () => {
  if (props.usuario) {
    form.put(`/users/${props.usuario.id}`);
  } else {
    form.post('/users');
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-4">

    <input v-model="form.nombre" placeholder="Nombre" class="w-full border px-3 py-2 rounded" />
    <input v-model="form.email" placeholder="Email" class="w-full border px-3 py-2 rounded" />

    <input
      type="password"
      v-model="form.password"
      placeholder="Password"
      class="w-full border px-3 py-2 rounded"
    />

    <select v-model="form.rol" class="w-full border px-3 py-2 rounded">
      <option value="ASESOR">Asesor</option>
      <option value="JEFE">Jefe</option>
    </select>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
      Guardar
    </button>

  </form>
</template>