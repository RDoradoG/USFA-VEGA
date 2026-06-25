<script setup>
import { useForm } from '@inertiajs/vue3';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';

const props = defineProps({
  promo: Object
});

const form = useForm({
  nombre: props.promo?.nombre || '',
  descripcion: props.promo?.descripcion || '',
  beca: props.promo?.beca || 0,
  activo: props.promo?.activo || true
});

/*const activeOptions = [
  {id: false, value: 'Activo'},
  {id: true, value: 'Inactivo'}
]*/

const submit = () => {
  if (props.promo) {
    form.put(`/promos/${props.promo.id}`);
  } else {
    form.post('/promos');
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-4">

    <Label required>Nombre</Label>
    <Input v-model="form.nombre" placeholder="Nombre" class="w-full border px-3 py-2 rounded" />

    <Label required>Descripción</Label>
    <Input v-model="form.descripcion" placeholder="Descripción" class="w-full border px-3 py-2 rounded" />

    <Label required>Beca</Label>
    <Input v-model="form.beca" placeholder="Beca %" class="w-full border px-3 py-2 rounded" />

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
      Guardar
    </button>

  </form>
</template>