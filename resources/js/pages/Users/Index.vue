<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, onMounted  } from 'vue';
import Modal from '@/components/Modal.vue';
import { PencilRuler, Trash2 } from 'lucide-vue-next';

import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import { useAPIs } from '@/composables/useAPI';
import { Pagination } from '@/components/ui/pagination'

const propsDef = defineProps({
  //usuarios: Object,
  filtros: Object
});

const { getAPIs } = useAPIs();

const showModal = ref(false);
const userToDelete = ref(null);

const search = ref(propsDef.filtros?.search || '');
const estado = ref(propsDef.filtros?.estado || true);
const rol = ref(propsDef.filtros?.rol || '');

const page = ref(1);
const orderBy = ref('id');
const orderDirection = ref('asc');
const usuarios = ref([]);

const openModal = (user) => {
  userToDelete.value = user;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  userToDelete.value = null;
};

const eliminar = () => {
  if (!userToDelete.value) return;

  router.delete(`/users/${userToDelete.value.id}`, {
    onSuccess: () => {
      closeModal();
      getRows(
        search.value,
        rol.value,
        estado.value,
        page.value,
        orderBy.value,
        orderDirection.value
      );
    }
  });
};

const roles = [
  {id: 'JEFE', nombre: 'Jefe'},
  {id: 'ASESOR', nombre: 'Asesor'}
]

const estados = [
  {id: true, nombre: 'Activo'},
  {id: false, nombre: 'Inactivo'}
]

const clearSearch = () => {
  search.value = '';
  estado.value = '';
  rol.value = '';
}

const getRows = async (search, rol, estado, page, orderBy, orderDirection) => {
  const filter = [
    {key: 'estado', value: estado},
    {key: 'rol', value: rol}
  ]

  getAPIs('users', search, page, 10, orderBy, orderDirection, filter)
  .then(response => {
    usuarios.value = response
  })
  .catch(error => console.error(error))
}

watch([search, estado, rol], () => getRows(
    search.value,
    rol.value,
    estado.value,
    page.value,
    orderBy.value,
    orderDirection.value
));

onMounted(() => getRows(
  search.value,
  rol.value,
  estado.value,
  page.value,
  orderBy.value,
  orderDirection.value
));

const changePage = (page_selected) => {
  page.value = page_selected
  getRows(
    search.value,
    rol.value,
    estado.value,
    page.value,
    orderBy.value,
    orderDirection.value
  )
}

</script>

<template>
  <div class="p-6 space-y-6">

    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Usuarios</h1>

      <Link href="/users/create" class="bg-blue-600 text-white px-4 py-2 rounded">
        + Nuevo
      </Link>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <div class="grid grid-cols-4 gap-4">

        <div>
          <Input v-model="search" placeholder="Buscar nombre, email..." class="mt-[21px]"/>
        </div>

        <div>
          <Label>Rol</Label>
          <Selectlist v-model="rol" :options="roles" :key="'id'" :value="'nombre'" />
        </div>

        <div>
          <Label>Estado</Label>
          <Selectlist v-model="estado" :options="estados" :key="'id'" :value="'nombre'" />
        </div>

        <button class="bg-gray-600 text-white w-full px-6 py-2 rounded h-10 w-24 mt-[21px] cursor-pointer" @click="clearSearch()">
          Vaciar
        </button>

      </div>
    </div>

    <table class="w-full text-sm">
      <thead class="bg-gray-100 text-gray-600">
        <tr>
          <th class="text-left px-4 py-2">Nombre</th>
          <th class="text-left px-4 py-2">Email</th>
          <th class="text-left px-4 py-2">Rol</th>
          <th class="text-left px-4 py-2">Estado</th>
          <th class="text-left px-4 py-2">Acciones</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="u in usuarios.rows" :key="u.id" class="border-t hover:bg-gray-50" >
          <td class="px-4 py-2">{{ u.nombre }}</td>
          <td class="px-4 py-2">{{ u.email }}</td>

          <td class="px-4 py-2">
            <span
              class="px-2 py-1 rounded text-xs"
              :class="u.rol === 'JEFE'
                ? 'bg-green-100 text-green-700'
                : 'bg-blue-100 text-blue-700'"
            >
              {{ u.rol }}
            </span>
          </td>

          <td class="px-4 py-2">
            <span
              class="px-2 py-1 rounded text-xs bg-green-100 text-green-700"
              v-if="u.activo"
            >
              ACTIVO
            </span>

            <span
              class="px-2 py-1 rounded text-xs bg-red-100 text-red-700"
              v-if="!u.activo"
            >
              INACTIVO
            </span>
          </td>

          <td class="text-right space-x-2 px-4 py-2">
            <div class="flex justify-end items-center gap-2">
              <Link :href="`/users/${u.id}/edit`" class="text-blue-600 flex items-center">
                <component :is="PencilRuler" />
              </Link>

              <button
                @click="openModal(u)"
                class="text-red-600 flex items-center cursor-pointer"
              >
                <component :is="Trash2" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- PAGINACION -->
    <div>
      <Pagination :meta="usuarios.meta" @set-page="changePage"/>
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