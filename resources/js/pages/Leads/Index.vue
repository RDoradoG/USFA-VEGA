<script setup>
import { router, usePage, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Modal from '@/components/Modal.vue';

import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import { useAPIs } from '@/composables/useAPI';
import axios from '@/lib/axios'

const { getAPIs } = useAPIs();

const { props } = usePage();
const user = props.auth.user;
const showModal = ref(false);
const leadToDelete = ref(null);

const openModal = (lead) => {
  leadToDelete.value = lead;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  leadToDelete.value = null;
};

const propsDef = defineProps({
  leads: Object,
  filtros: Object,
  estados: Array,
  usuarios: Array,
  fuentes: Array
});

// FILTROS
const search = ref(propsDef.filtros?.search || '');
const estado = ref(propsDef.filtros?.estado || '');
const usuario = ref(propsDef.filtros?.usuario || '');
const interes = ref(propsDef.filtros?.interes || '');
const fuente = ref(propsDef.filtros?.fuente || '');


// WATCH (auto búsqueda)
watch([search, estado, usuario, interes, fuente], () => {
  /*router.get('/leads', {
    search: search.value,
    estado: estado.value,
    usuario: usuario.value,
    interes: interes.value,
    fuente: fuente.value,
  }, {
    preserveState: true,
    replace: true
  });*/

  const res = getRows(search.value, estado.value, usuario.value, interes.value, fuente.value)
  console.log(res)
});

const getRows = async (search, estado, usuario, interes, fuente) => {
  const filter = [
    {key: 'estado', value: estado},
    {key: 'usuario', value: usuario},
    {key: 'interes', value: interes},
    {key: 'fuente', value: fuente}
  ]
  getAPIs('user', search, 1, 10, 'id', 'asc', filter)
  .then(response => {
      console.log(response)
  })
  .catch(error => console.error(error))
}

const interests = [
  {id: 'Alto', value: 'Alto'},
  {id: 'Medio', value: 'Medio'},
  {id: 'Bajo', value: 'Bajo'}
]

// PERMISOS
const isJefe = user.rol === 'JEFE';

// BADGES
const badgeEstado = (estado) => {
  const map = {
    NUEVO: 'bg-blue-100 text-blue-700',
    CONTACTADO: 'bg-yellow-100 text-yellow-700',
    INTERESADO: 'bg-green-100 text-green-700',
    NO_INTERESADO: 'bg-red-100 text-red-700',
  };
  return map[estado] || 'bg-gray-100 text-gray-700';
};

const badgeInteres = (nivel) => {
  const map = {
    ALTO: 'bg-green-100 text-green-700',
    MEDIO: 'bg-yellow-100 text-yellow-700',
    BAJO: 'bg-red-100 text-red-700',
  };
  return map[nivel];
};

// DELETE
const eliminar = (id) => {
  if (!leadToDelete.value) return;

  router.delete(`/leads/${leadToDelete.value.id}`, {
    onSuccess: closeModal
  });
};

const clearSearch = () => {
  search.value = '';
  estado.value = '';
  usuario.value = '';
  interes.value = '';
  fuente.value = '';
}
</script>

<template>
  <div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Leads</h1>

      <Link
        v-if="isJefe"
        href="/leads/create"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + Nuevo Lead
      </Link>
    </div>

    <!-- FILTROS -->
    <div class="bg-white p-4 rounded shadow grid grid-cols-6 gap-4">

      <div>
        <Input v-model="search" placeholder="Buscar nombre, celular..." class="mt-[21px]"/>
      </div>

      <div>
        <Label>Estado</Label>
        <Selectlist v-model="estado" :options="estados" :key="'id'" :value="'nombre'" />
      </div>

      <div>
        <Label>Asesor</Label>
        <Selectlist v-model="usuario" :options="usuarios" :key="'id'" :value="'nombre'" />
      </div>

      <div>
        <Label>Interés</Label>
        <Selectlist v-model="interes" :options="interests" />
      </div>

      <div>
        <Label>Fuente</Label>
        <Selectlist v-model="fuente" :options="fuentes" :key="'id'" :value="'nombre'" />
      </div>

      <button class="bg-gray-600 text-white px-6 py-2 rounded h-10 w-24 mt-[21px] cursor-pointer" @click="clearSearch()">
        Vaciar
      </button>
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded shadow overflow-x-auto">

      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="text-left px-4 py-2">Nombre</th>
            <th class="text-left px-4 py-2">Contacto</th>
            <th class="text-left px-4 py-2">Ciudad</th>
            <th class="text-left px-4 py-2">Carrera</th>
            <th class="text-left px-4 py-2">Estado</th>
            <th class="text-left px-4 py-2">Interés</th>
            <th class="text-left px-4 py-2">Asesor</th>
            <th class="text-left px-4 py-2">Últ. contacto</th>
            <th class="px-4 py-2 text-right">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="lead in leads.data"
            :key="lead.id"
            class="border-t hover:bg-gray-50"
          >

            <td class="px-4 py-2">
              <div class="font-medium">{{ lead.full_name }}</div>
              <!--div class="text-xs text-gray-400">
                {{ lead.email }}
              </div-->
            </td>

            <td class="px-4 py-2">
              {{ lead.celular }}
            </td>

            <td class="px-4 py-2">
              {{ lead.ciudad }}
            </td>

            <td class="px-4 py-2">
              {{ lead.carrera?.nombre }}
            </td>

            <!-- ESTADO -->
            <td class="px-4 py-2">
              <span
                class="px-2 py-1 rounded text-xs"
                :class="badgeEstado(lead.estado?.nombre)"
              >
                {{ lead.estado?.nombre }}
              </span>
            </td>

            <!-- INTERES -->
            <td class="px-4 py-2">
              <span
                class="px-2 py-1 rounded text-xs"
                :class="badgeInteres(lead.interes_nivel)"
              >
                {{ lead.interes_nivel }}
              </span>
            </td>

            <td class="px-4 py-2">
              {{ lead.usuario?.nombre }}
            </td>

            <td class="px-4 py-2">
              <span v-if="lead.ultimo_contacto">
                {{ new Date(lead.ultimo_contacto).toLocaleDateString() }}
              </span>
              <span v-else class="text-gray-400">—</span>
            </td>

            <!-- ACCIONES -->
            <td class="px-4 py-2 text-right space-x-2">

              <Link
                :href="`/leads/${lead.id}/edit`"
                class="text-blue-600 hover:underline"
              >
                Editar
              </Link>

              <button
                v-if="isJefe"
                @click="openModal(lead)"
                class="text-red-600 hover:underline"
              >
                Eliminar
              </button>

            </td>

          </tr>
        </tbody>

      </table>

    </div>

    <!-- PAGINACION -->
    <div class="flex justify-center gap-2">

      <button
        v-for="link in leads.links"
        :key="link.label"
        v-html="link.label"
        @click="router.visit(link.url)"
        :disabled="!link.url"
        class="px-3 py-1 border rounded text-sm"
        :class="{
          'bg-blue-600 text-white': link.active,
          'text-gray-400': !link.url
        }"
      />

    </div>

    <!-- MODAL -->
    <Modal :show="showModal" @close="closeModal">
      <h2 class="text-lg font-bold mb-2">
        Confirmar eliminación
      </h2>

      <p class="text-sm text-gray-600 mb-4">
        ¿Seguro que deseas eliminar a
        <strong>{{ userToDelete?.nombre }}</strong>?
      </p>

      <div class="flex justify-end gap-2">
        <button
          @click="closeModal"
          class="px-4 py-2 border rounded"
        >
          Cancelar
        </button>

        <button
          @click="eliminar"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Eliminar
        </button>
      </div>
    </Modal>

  </div>
</template>