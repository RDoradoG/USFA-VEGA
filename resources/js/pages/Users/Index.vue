<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';

const props = defineProps({
  usuarios: Object
});

const showModal = ref(false);
const userToDelete = ref(null);

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
    onSuccess: closeModal
  });
};
</script>

<template>
  <div class="p-6 space-y-6">

    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Usuarios</h1>

      <Link href="/users/create" class="bg-blue-600 text-white px-4 py-2 rounded">
        + Nuevo
      </Link>
    </div>

    <table class="w-full text-sm">
      <thead>
        <tr>
          <th class="px-4 py-2 border">Nombre</th>
          <th class="px-4 py-2 border">Email</th>
          <th class="px-4 py-2 border">Rol</th>
          <th class="px-4 py-2 border"></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="u in usuarios.data" :key="u.id">
          <td class="px-4 py-2 border">{{ u.nombre }}</td>
          <td class="px-4 py-2 border">{{ u.email }}</td>
          <td class="px-4 py-2 border">
            <span
              class="px-2 py-1 rounded text-xs"
              :class="u.rol === 'JEFE'
                ? 'bg-green-100 text-green-700'
                : 'bg-blue-100 text-blue-700'"
            >
              {{ u.rol }}
            </span>
          </td>

          <td class="text-right space-x-2 px-4 py-2 border">
            <Link :href="`/users/${u.id}/edit`" class="text-blue-600">
              Editar
            </Link>

            <button
              @click="openModal(u)"
              class="text-red-600"
            >
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>

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