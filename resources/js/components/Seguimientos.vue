<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps({
  lead: Object
});

const { props: page } = usePage();
const user = page.auth.user;
const seguimientos = ref([]);

const form = useForm({
  tipo: '',
  resultado: '',
  observacion: ''
});

const types = [
  {id: 'LLAMADA', value: 'LLAMADA'},
  {id: 'WHATSAPP', value: 'WHATSAPP'},
  {id: 'EMAIL', value: 'EMAIL'},
  {id: 'OTRO', value: 'OTRO'}
]

const results = [
  {id: 'NO_RESPONDE', value: 'NO RESPONDE'},
  {id: 'INTERESADO', value: 'INTERESADO'},
  {id: 'NO_INTERESADO', value: 'NO INTERESADO'},
  {id: 'SEGUIMIENTO', value: 'SEGUIMIENTO'}
]

const searchResult = (id) => {
  const type = results.find(res => res.id == id)
  return type === undefined ? '' : type.value
}

const submit = () => {
  form.post(`/leads/${props.lead.id}/seguimientos`, {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    }
  });
};

const refreshLead = (newLead) => {
  seguimientos.value = [];

  newLead.seguimientos.forEach(seg => {
    seg.resultado = searchResult(seg.resultado);
    seguimientos.value.push(seg);
  });
}

onMounted(() => refreshLead(props.lead));
watch(() => props.lead, (newLead) => refreshLead(newLead));
</script>

<template>
  <div class="mt-8">

    <h2 class="text-lg font-bold mb-4">Seguimientos</h2>

    <!-- FORM -->
    <form @submit.prevent="submit" class="mb-6">

      <div class="grid grid-cols-2 gap-3">

        <div>
          <Label>Tipo</Label>
          <Selectlist v-model="form.tipo" :options="types" />
        </div>

        <div>
          <Label>Resultado</Label>
          <Selectlist v-model="form.resultado" :options="results" />
        </div>

      </div>

      <div>
        <Label>Observación</Label>
        <Input v-model="form.observacion" placeholder="Observación" class="input" />
      </div>

      <button class="col-span-3 bg-blue-500 text-white py-2 rounded mt-2 w-full">
        Registrar seguimiento
      </button>
    </form>

    <!-- LISTA -->
    <div class="space-y-3">

      <div
        v-for="seg in seguimientos"
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