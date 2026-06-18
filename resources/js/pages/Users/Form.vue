<script setup>
import { useForm } from '@inertiajs/vue3';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';

const props = defineProps({
  usuario: Object
});

const form = useForm({
  nombre: props.usuario?.nombre || '',
  email: props.usuario?.email || '',
  password: '',
  rol: props.usuario?.rol || 'ASESOR'
});

const roleOptions = [
  {id: 'ASESOR', value: 'Asesor'},
  {id: 'JEFE', value: 'Jefe'}
]

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

    <Label required>Nombre</Label>
    <Input v-model="form.nombre" placeholder="Nombre" class="w-full border px-3 py-2 rounded" />

    <Label required>E-mail</Label>
    <Input v-model="form.email" placeholder="Email" class="w-full border px-3 py-2 rounded" />

    <Label required>Password</Label>
    <Input
      type="password"
      v-model="form.password"
      placeholder="Password"
      class="w-full border px-3 py-2 rounded"
    />

    <Label required>Rol</Label>
    <Selectlist v-model="form.rol" :options="roleOptions" />

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
      Guardar
    </button>

  </form>
</template>