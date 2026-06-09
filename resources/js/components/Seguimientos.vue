<script setup>
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  lead: Object
});

const { props: page } = usePage();
const user = page.auth.user;

const form = useForm({
  tipo: '',
  resultado: '',
  observacion: ''
});

const submit = () => {
  form.post(`/leads/${props.lead.id}/seguimientos`, {
    preserveScroll: true
  });
};
</script>

<template>
  <div class="mt-8">

    <h2 class="text-lg font-bold mb-4">Seguimientos</h2>

    <!-- FORM -->
    <form @submit.prevent="submit" class="grid grid-cols-3 gap-3 mb-6">

      <select v-model="form.tipo" class="input">
        <option value="">Tipo</option>
        <option>LLAMADA</option>
        <option>WHATSAPP</option>
        <option>EMAIL</option>
        <option>OTRO</option>
      </select>

      <select v-model="form.resultado" class="input">
        <option value="">Resultado</option>
        <option>NO_RESPONDE</option>
        <option>INTERESADO</option>
        <option>NO_INTERESADO</option>
        <option>SEGUIMIENTO</option>
      </select>

      <input v-model="form.observacion" placeholder="Observación" class="input" />

      <button class="col-span-3 bg-blue-500 text-white py-2 rounded">
        Registrar seguimiento
      </button>
    </form>

    <!-- LISTA -->
    <div class="space-y-3">

      <div
        v-for="seg in lead.seguimientos"
        :key="seg.id"
        class="border p-3 rounded bg-gray-50"
      >
        <div class="flex justify-between">

          <div>
            <strong>{{ seg.tipo }}</strong> -
            <span>{{ seg.resultado }}</span>
          </div>

          <div class="text-sm text-gray-500">
            {{ new Date(seg.fecha_contacto).toLocaleString() }}
          </div>
        </div>

        <div class="text-sm text-gray-700 mt-1">
          {{ seg.observacion }}
        </div>

        <div class="text-xs text-gray-400 mt-1">
          Por: {{ seg.usuario?.nombre }}
        </div>

        <!-- DELETE SOLO JEFE -->
        <button
          v-if="user.rol === 'JEFE'"
          @click="$inertia.delete(`/seguimientos/${seg.id}`)"
          class="text-red-500 text-xs mt-2"
        >
          Eliminar
        </button>
      </div>

    </div>

  </div>
</template>