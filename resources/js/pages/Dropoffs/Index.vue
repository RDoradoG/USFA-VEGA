<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { ref, watch, onMounted  } from 'vue';
import Modal from '@/components/Modal.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import { useAPIs } from '@/composables/useAPI';
import { Pagination } from '@/components/ui/pagination'
import { PencilRuler, ArrowUp, ArrowDown } from 'lucide-vue-next';

const { getAPIs, postAPIs } = useAPIs();
const { props } = usePage();

const user = props.auth.user;
const showModal = ref(false);

const openModal = () => {
  showModal.value = true;
  syncLoading.value = 'init';
  syncResultShow.value = 'estudiantes'
}

const closeModal = () => showModal.value = false;

const propsDef = defineProps({
  filtros: Object,
  estados: Array,
  usuarios: Array,
  seguimientoTipos: Array
});

// FILTROS
const search = ref(propsDef.filtros?.search || '');
const estado = ref(propsDef.filtros?.estado || '');
const usuario = ref(propsDef.filtros?.usuario || '');
const seguimientoTipo = ref(propsDef.filtros?.seguimientoTipo || '');
const fechaDesde = ref(propsDef.filtros?.fecha_desde || '');
const fechaHasta = ref(propsDef.filtros?.fecha_hasta || '');

const dropoffs = ref([]);
const page = ref(1);
const orderBy = ref('id');
const orderDirection = ref('asc');
const syncLoading = ref('init');
const syncResponse = ref({});
const syncResultShow = ref('estudiantes');

const getInitialRow = () => getRows(
  search.value,
  estado.value,
  usuario.value,
  seguimientoTipo.value,
  fechaDesde.value,
  fechaHasta.value,
  page.value,
  orderBy.value,
  orderDirection.value
)

// WATCH (auto búsqueda)
watch([search, estado, usuario, seguimientoTipo, fechaDesde, fechaHasta], () => getInitialRow());

onMounted(() => getInitialRow());

const changePage = (page_selected) => {
  page.value = page_selected
  getInitialRow()
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
    seguimientoTipo.value,
    fechaDesde.value,
    fechaHasta.value,
    1,
    orderBy.value,
    orderDirection.value
  )
}

const getRows = async (search, estado, usuario, seguimientoTipo, fechaDesde, fechaHasta, page, orderBy, orderDirection) => {
  const filter = [
    {key: 'estado', value: estado},
    {key: 'usuario', value: usuario},
    {key: 'seguimientoTipo', value: seguimientoTipo},
    {key: 'fecha_desde', value: fechaDesde },
    {key: 'fecha_hasta', value: fechaHasta }
  ]

  getAPIs('dropoffs', search, page, 10, orderBy, orderDirection, filter)
  .then(response => {
    dropoffs.value = {
      meta: response.meta,
      rows: processData(response.rows)
    }
  })
  .catch(error => console.error(error))
}

const processData = (records) => {
  let rows = []

  records.forEach(record => {
    record.abandono = verifyType(record.abandonos_deserciones, 1) >= 0
    record.desercion = verifyType(record.abandonos_deserciones, 2) >= 0
    rows.push(record)
  })

  return rows
}

const verifyType = (abandonos_deserciones, id) => abandonos_deserciones.findIndex(dropoff => dropoff.seguimiento_tipo.id == id)

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

const clearSearch = () => {
  search.value = '';
  estado.value = '';
  usuario.value = '';
  seguimientoTipo.value = '';
  fechaDesde.value = '';
  fechaHasta.value = '';
}

const syncDropoffs = () => {
  syncLoading.value = 'load'

  postAPIs('dropoffs/sync', null)
  .then(response => {
    syncLoading.value = 'complete'
    syncResponse.value = response
    console.log(response)
  })
  .catch(error => {
    syncLoading.value = 'complete'
    console.error(error)
  })
}

const showSyncResult = (tab) => syncResultShow.value = tab

</script>

<template>
  <div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Abandonos y Deserciones</h1>

      <div>
        <button
          @click="openModal()"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          ↻ Sincronizar
        </button>
      </div>
    </div>

    <!-- FILTROS -->

    <div class="bg-white p-4 rounded shadow">
      <div class="grid grid-cols-5 gap-4">

        <div>
          <Input v-model="search" placeholder="Buscar nombre..." class="mt-[21px]"/>
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
          <Label>Tipo de Seguimiento</Label>
          <Selectlist v-model="seguimientoTipo" :options="seguimientoTipos" :key="'id'" :value="'nombre'" />
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

            <th class="text-left px-4 py-2">
              <div class="flex items-center gap-1">
                <span>Tipo</span>
              </div>
            </th>
            <th class="text-left px-4 py-2" @click="sort('estado')">
              <div class="flex items-center gap-1">
                <span>Estado</span>
                <component v-if="orderBy == 'estado'" :is="orderDirection === 'asc' ? ArrowUp : ArrowDown" />
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
            <th class="px-4 py-2 text-right">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="dropoff in dropoffs.rows"
            :key="dropoff.id"
            class="border-t hover:bg-gray-50">
            <td class="px-4 py-2">
              <div class="font-medium">{{ dropoff.estudiante?.full_name }}</div>
            </td>
            <td class="px-4 py-2 flex flex-col items-start gap-1">
              <span class="px-2 py-1 rounded text-xs bg-orange-500" v-if="dropoff.abandono">
                Abandono
              </span>
              <span class="px-2 py-1 rounded text-xs bg-teal-500" v-if="dropoff.desercion">
                Deserción
              </span>
            </td>
            <!-- ESTADO -->
            <td class="px-4 py-2">
              <span class="px-2 py-1 rounded text-xs"
                :style="colors ? { backgroundColor: '#ffffff'} : null"
                :class="badgeEstado(dropoff.seguimiento_estado?.color)">
                {{ dropoff.seguimiento_estado?.nombre }}
              </span>
            </td>

            <td class="px-4 py-2">
              {{ dropoff.usuario?.nombre }}
            </td>

            <td class="px-4 py-2">
              <span v-if="dropoff.ultimo_contacto">
                {{ new Date(dropoff.ultimo_contacto).toLocaleDateString() }}
              </span>
              <span v-else class="text-gray-400">—</span>
            </td>

            <!-- ACCIONES -->
            <td class="px-4 py-2 text-right">
              <div class="flex justify-end items-center gap-2">
                <Link :href="`/dropoffs/${dropoff.id}/edit`"
                  class="text-blue-600 hover:underline flex items-center">
                  <component :is="PencilRuler" />
                </Link>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINACION -->
     <div>
        <Pagination :meta="dropoffs.meta" @set-page="changePage"/>
    </div>

    <!-- MODAL -->
    <Modal :show="showModal" @close="closeModal">
      <h2 class="text-lg font-bold mb-2" v-if="syncLoading == 'init'">
        Confirmar sincronización
      </h2>

      <h2 class="text-lg font-bold mb-2" v-if="syncLoading == 'load'">
        Sincronizando
      </h2>

      <h2 class="text-lg font-bold mb-2" v-if="syncLoading == 'complete'">
        Sincronización Completada
      </h2>

      <p class="text-sm text-gray-600 mb-4" v-if="syncLoading == 'init'">
        ¿Seguro que deseas sincronizar?
      </p>

      <p class="text-sm text-gray-600 mb-4" v-if="syncLoading == 'load'">
        Sincronizando...
      </p>

      <div class="flex-1 space-y-6 overflow-y-auto p-6" v-if="syncLoading == 'complete'">
        <!-- ESTUDIANTES -->
        <div class="mb-2 flex items-center justify-between cursor-pointer" @click="showSyncResult('estudiantes')">
          <h3 class="text-sm font-semibold text-gray-700">
            Resultado sincronización
          </h3>

          <span class="rounded-full px-3 py-1 text-xs font-medium"
            :class="
              syncResponse?.success
              ? 'bg-green-100 text-green-700'
              : 'bg-red-100 text-red-700'">
            {{ syncResponse?.success ? 'Completado' : 'Error' }}
          </span>
        </div>

        <div class="max-h-56 overflow-auto rounded-lg bg-gray-950 p-4 shadow-inner" v-if="syncResultShow == 'estudiantes'">
          <pre class="whitespace-pre-wrap font-mono text-xs leading-5 text-green-400">{{ syncResponse?.output }}</pre>
        </div>   
      </div>

      <div class="flex justify-end gap-2" v-if="syncLoading == 'init'">
        <button @click="closeModal" class="px-4 py-2 border rounded bg-gray-200 hover:bg-gray-300">
          Cancelar
        </button>

        <button @click="syncDropoffs" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
          Sincronizar
        </button>
      </div>

      <div class="flex justify-end gap-2" v-if="syncLoading == 'complete'">
        <button @click="closeModal" class="px-4 py-2 border rounded bg-gray-200 hover:bg-gray-300">
          OK
        </button>
      </div>
    </Modal>
  </div>
</template>