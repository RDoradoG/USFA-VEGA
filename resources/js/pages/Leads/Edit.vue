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
  'fuentes',
  'codigos_pais',
  'promociones',
  'history_lead'
]);

const isJefe = user.rol === 'JEFE';
const isOwner = propsDef.lead.usuario_id === user.id;
const alreadyDone = propsDef.lead.estado_id == 6;

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
          :codigos_pais="codigos_pais"
          :promociones="promociones"
        />

        <!-- MENSAJE SI NO PUEDE EDITAR -->
        <div
          v-if="!canEdit"
          class="mt-4 text-sm text-red-500"
        >
          No tienes permisos para editar este lead.
        </div>

        <div class="mb-4 border-b pb-2 mt-2">
          <h2 class="font-semibold text-gray-700">
            Historial del Lead
          </h2>
        </div>

        <div class="mt-4 space-y-4">
          <div v-if="history_lead.length === 0" class="text-sm text-gray-400">
            No hay historial disponible.
          </div>

          <div v-for="(item, index) in history_lead" :key="index" class="flex gap-3">

            <!-- Línea / punto -->
            <div class="flex flex-col items-center">
              <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
              <div v-if="index !== history_lead.length - 1" class="w-px flex-1 bg-gray-300"></div>
            </div>

            <!-- Contenido -->
            <div class="bg-gray-50 border rounded p-3 w-full shadow-sm">
              
              <p class="text-sm text-gray-700">
                {{ item.mensaje || 'Sin mensaje' }}
              </p>

              <!-- opcional: fecha si luego la agregas -->
              <p v-if="item.created_at" class="text-xs text-gray-400 mt-1">
                {{ new Date(item.created_at).toLocaleString() }}
              </p>

            </div>
          </div>
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
          <div><strong>Promoción:</strong> {{ lead.promocion?.nombre || '-' }}</div>

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
        <div class="bg-white p-4 rounded shadow" v-if="! alreadyDone">

          <Seguimientos :lead="lead" />

        </div>

      </div>

    </div>

  </div>
</template>