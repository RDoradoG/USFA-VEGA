<script setup>
import { router, usePage, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted  } from 'vue';
import Modal from '@/components/Modal.vue';

import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import { useAPIs } from '@/composables/useAPI';
import { Pagination } from '@/components/ui/pagination'
import axios from '@/lib/axios'

import { PencilRuler, Trash2, ArrowUp, ArrowDown } from 'lucide-vue-next';

const { getAPIs } = useAPIs();
const { props } = usePage();

const user = props.auth.user;
const showModal = ref(false);
const showModalCSVResult = ref(false);
const showModalCSV = ref(false);
const leadToDelete = ref(null);

const created = ref(0)
const row_errors = ref([])

const openModal = (lead) => {
  leadToDelete.value = lead;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  leadToDelete.value = null;
};

const closeModalCSV = () => showModalCSV.value = false;
const openModalCSV = () => showModalCSV.value = true;

const closeModalCSVResult = () => showModalCSVResult.value = false;
const openModalCSVResult = () => showModalCSVResult.value = true;

const propsDef = defineProps({
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
const fechaDesde = ref(propsDef.filtros?.fecha_desde || '');
const fechaHasta = ref(propsDef.filtros?.fecha_hasta || '');

const leads = ref([]);
const page = ref(1);
const orderBy = ref('id');
const orderDirection = ref('asc');

const form = useForm({
  file: null
});

const getInitialRowd = () => getRows(
  search.value,
  estado.value,
  usuario.value,
  interes.value,
  fuente.value,
  fechaDesde.value,
  fechaHasta.value,
  page.value,
  orderBy.value,
  orderDirection.value
)

// WATCH (auto búsqueda)
watch([search, estado, usuario, interes, fuente, fechaDesde, fechaHasta], () => getInitialRowd());

onMounted(() => getInitialRowd());

const changePage = (page_selected) => {
  page.value = page_selected
  getInitialRowd()
}

const sort = (column) => {
  if (orderBy.value === column) {
    orderDirection.value = orderDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    orderBy.value = column
    orderDirection.value = 'asc'
  }

  getRows(
    search.value,
    estado.value,
    usuario.value,
    interes.value,
    fuente.value,
    fechaDesde.value,
    fechaHasta.value,
    1,
    orderBy.value,
    orderDirection.value
  )
}

const getRows = async (search, estado, usuario, interes, fuente, fechaDesde, fechaHasta, page, orderBy, orderDirection) => {
  const filter = [
    {key: 'estado', value: estado},
    {key: 'usuario', value: usuario},
    {key: 'interes', value: interes},
    {key: 'fuente', value: fuente},
    {key: 'fecha_desde', value: fechaDesde },
    {key: 'fecha_hasta', value: fechaHasta }
  ]

  getAPIs('leads', search, page, 10, orderBy, orderDirection, filter)
  .then(response => {
    leads.value = response
  })
  .catch(error => console.error(error))
}

const interests = [
  {id: 'Alto', value: 'Alto'},
  {id: 'Medio', value: 'Medio'},
  {id: 'Bajo', value: 'Bajo'}
]

// PERMISOS
const isJefe = user.rol === 'JEFE' || user.rol === 'SUPERADMIN';

// BADGES
const badgeEstado = (color) => {
  const colorMap = {
    red: 'bg-red-300 text-red-800',
    green: 'bg-green-300 text-green-800',
    emerald: 'bg-emerald-300 text-emerald-800',
    cyan: 'bg-cyan-300 text-cyan-800',
    yellow: 'bg-yellow-300 text-yellow-800',
    orange: 'bg-orange-300 text-orange-800',
    grey: 'bg-grey-300 text-grey-800',
  }
  return colorMap[color] || 'bg-gray-100 text-gray-700';
};

const badgeInteres = (nivel) => {
  const map = {
    Alto: 'bg-green-300 text-green-800',
    Medio: 'bg-yellow-300 text-yellow-800',
    Bajo: 'bg-red-300 text-red-800',
  };
  return map[nivel];
};

// DELETE
const eliminar = (id) => {
  if (!leadToDelete.value) return;

  router.delete(`/leads/${leadToDelete.value.id}`, {
    onSuccess: () => {
      getInitialRowd()
      closeModal()
    }
  });
};

const clearSearch = () => {
  search.value = '';
  estado.value = '';
  usuario.value = '';
  interes.value = '';
  fuente.value = '';
  fechaDesde.value = '';
  fechaHasta.value = '';
}

const handleFileUpload = (e) => {
  const selected = e.target.files[0];

  if (!selected) return;

  if (!selected.name.endsWith('.csv')) {
    alert('Solo se permiten archivos CSV');
    return;
  }

  form.file = selected;
};

const runCSV = async () => {
  if (!form.file) {
    alert('Selecciona un archivo');
    return;
  }

  const formData = new FormData()
  formData.append('file', form.file)

  const response = await axios.post('/leads/upload-csv', formData)

  created.value = response.data.created
  row_errors.value = response.data.row_errors

  closeModalCSV()
  openModalCSVResult()
  getInitialRowd()
};

/*const sortIcon = (column) => {
  if (orderBy.value !== column) return '↕';
  return orderDirection.value === 'asc' ? '↑' : '↓';
};*/

</script>

<template>
  <div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Leads</h1>

      <div>
        <button
          v-if="isJefe"
          @click="openModalCSV"
          class="bg-green-600 text-white mr-4 px-4 py-1 rounded hover:bg-green-700"
        >
          Subir CSV
        </button>

        <Link
          href="/leads/create"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          + Nuevo Lead
        </Link>
      </div>
    </div>

    <!-- FILTROS -->

    <div class="bg-white p-4 rounded shadow">
      <div class="grid grid-cols-5 gap-4">

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
      </div>

      <div class="grid grid-cols-5 gap-4">
        <div></div>

        <div>
          <Label>Desde</Label>
          <Input type="date" v-model="fechaDesde" />
        </div>

        <div>
          <Label>Hasta</Label>
          <Input type="date" v-model="fechaHasta" />
        </div>

        <div></div>

        <button class="bg-gray-600 text-white w-full px-6 py-2 rounded h-10 w-24 mt-[21px] cursor-pointer" @click="clearSearch()">
          Vaciar
        </button>

      </div>
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded shadow overflow-x-auto">

      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="text-left px-4 py-2" @click="sort('nombre')">
              <div class="flex items-center gap-1">
                <span>Nombre</span>
                <component v-if="orderBy == 'nombre'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('celular')">
              <div class="flex items-center gap-1">
                <span>Contacto</span>
                <component v-if="orderBy == 'celular'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('ciudad')">
              <div class="flex items-center gap-1">
                <span>Ciudad</span>
                <component v-if="orderBy == 'ciudad'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('carrera')">
              <div class="flex items-center gap-1">
                <span>Carrera</span>
                <component v-if="orderBy == 'carrera'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('estado')">
              <div class="flex items-center gap-1">
                <span>Estado</span>
                <component v-if="orderBy == 'estado'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('interes_nivel')">
              <div class="flex items-center gap-1">
                <span>Interés</span>
                <component v-if="orderBy == 'interes_nivel'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('usuario')">
              <div class="flex items-center gap-1">
                <span>Asesor</span>
                <component v-if="orderBy == 'usuario'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('ultimo_contacto')">
              <div class="flex items-center gap-1">
                <span>Últ. contacto</span>
                <component v-if="orderBy == 'ultimo_contacto'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('fecha_registro')">
              <div class="flex items-center gap-1">
                <span>Fecha Registro</span>
                <component v-if="orderBy == 'fecha_registro'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
              </div>
            </th>
            <th class="px-4 py-2 text-right">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="lead in leads.rows"
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
                :style="colors ? { backgroundColor: '#ffffff'} : null"
                :class="badgeEstado(lead.estado?.color)"
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

            <td class="px-4 py-2">
              {{ new Date(lead.fecha_registro).toLocaleDateString() }}
            </td>

            <!-- ACCIONES -->
            <td class="px-4 py-2 text-right">
              <div class="flex justify-end items-center gap-2">

                <Link
                  :href="`/leads/${lead.id}/edit`"
                  class="text-blue-600 hover:underline flex items-center"
                >
                  <component :is="PencilRuler" />
                </Link>

                <button
                  v-if="isJefe"
                  @click="openModal(lead)"
                  class="text-red-600 hover:underline flex items-center"
                >
                  <component :is="Trash2" />
                </button>

              </div>

            </td>

          </tr>
        </tbody>

      </table>

    </div>

    <!-- PAGINACION -->
     <div>
        <Pagination :meta="leads.meta" @set-page="changePage"/>
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
          class="px-4 py-2 border rounded bg-gray-200 hover:bg-gray-300"
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

    <Modal :show="showModalCSV" @close="closeModalCSV">
      <h2 class="text-lg font-bold mb-2">
        Subir archivo
      </h2>

      <Label>Importar desde CSV</Label>
      <input
        type="file"
        accept=".csv"
        @change="handleFileUpload"
        class="border rounded px-2 py-1 mt-1"
      />

      <div v-if="form.errors.file" class="text-red-500 text-sm mt-1">
        {{ form.errors.file }}
      </div>


      <div class="flex justify-end gap-2">
        <button
          @click="closeModalCSV"
          class="px-4 py-2 border rounded bg-gray-200 hover:bg-gray-300"
        >
          Cancelar
        </button>

        <button
          @click="runCSV"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Ejecutar
        </button>
      </div>
    </Modal>

    <Modal :show="showModalCSVResult" @close="closeModalCSVResult">
      <h2 class="text-lg font-bold mb-2">
        Resultado
      </h2>

      <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        Proceso Terminado - Insertados: {{ created }}
      </div>

      <div v-if="row_errors.length" class="bg-red-100 p-4 rounded">
        <h3 class="text-red-700 font-bold mb-2">
            Errores encontrados ({{ row_errors.length }})
        </h3>

        <div class="overflow-auto max-h-64">
          <table class="min-w-full text-sm border">
            <thead class="bg-red-200">
              <tr>
                <th class="p-2 border">Fila</th>
                <th class="p-2 border">Error</th>
                <th class="p-2 border">Datos</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(error, i) in row_errors" :key="i" class="bg-white">
                <td class="p-2 border">{{ error.row }}</td>
                <td class="p-2 border text-red-600">
                    {{ error.error }}
                </td>
                <td class="p-2 border text-xs">
                    {{ error.data.join(', ') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end gap-2">
        <button
          @click="closeModalCSVResult"
          class="px-4 py-2 border rounded bg-gray-200 hover:bg-gray-300"
        >
          Aceptar
        </button>
      </div>
    </Modal>

  </div>
</template>