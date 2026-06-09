<script setup>
import Form from './Form.vue';
import Seguimientos from '@/components/Seguimientos.vue';
import { usePage, Link } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth.user;

const propsDef = defineProps([
  'lead',
  'sedes',
  'carreras',
  'horarios',
  'estados',
  'usuarios',
  'fuentes'
]);

const isJefe = user.rol === 'JEFE';
const isOwner = propsDef.lead.usuario_id === user.id;

// permisos
const canEdit = isJefe || isOwner;

</script>

<template>
  <div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-xl font-bold">
          Editar Lead
        </h1>
        <p class="text-sm text-gray-500">
          {{ lead.nombre }} - {{ lead.celular }}
        </p>
      </div>

      <Link
        href="/leads"
        class="text-gray-600 hover:underline"
      >
        ← Volver
      </Link>
    </div>

    <!-- GRID PRINCIPAL -->
    <div class="grid grid-cols-3 gap-6">

      <!-- FORMULARIO -->
      <div class="col-span-2 bg-white p-4 rounded shadow">

        <div class="mb-4 border-b pb-2">
          <h2 class="font-semibold text-gray-700">
            Información del Lead
          </h2>
        </div>

        <Form
          :lead="lead"
          :sedes="sedes"
          :carreras="carreras"
          :horarios="horarios"
          :estados="estados"
          :usuarios="usuarios"
          :fuentes="fuentes"
        />

        <!-- MENSAJE SI NO PUEDE EDITAR -->
        <div
          v-if="!canEdit"
          class="mt-4 text-sm text-red-500"
        >
          No tienes permisos para editar este lead.
        </div>

      </div>

      <!-- SIDEBAR -->
      <div class="space-y-4">

        <!-- INFO RAPIDA -->
        <div class="bg-white p-4 rounded shadow text-sm space-y-2">

          <h2 class="font-semibold text-gray-700 mb-2">
            Información rápida
          </h2>

          <div><strong>Estado:</strong> {{ lead.estado?.nombre }}</div>
          <div><strong>Interés:</strong> {{ lead.interes_nivel }}</div>
          <div><strong>Asesor:</strong> {{ lead.usuario?.nombre }}</div>
          <div><strong>Fuente:</strong> {{ lead.fuente?.nombre }}</div>

          <div>
            <strong>Registro:</strong><br>
            {{ new Date(lead.fecha_registro).toLocaleString() }}
          </div>

          <div v-if="lead.ultimo_contacto">
            <strong>Último contacto:</strong><br>
            {{ new Date(lead.ultimo_contacto).toLocaleString() }}
          </div>

        </div>

        <!-- SEGUIMIENTOS -->
        <div class="bg-white p-4 rounded shadow">

          <Seguimientos :lead="lead" />

        </div>

      </div>

    </div>

  </div>
</template>