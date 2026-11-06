<script setup>
import Form from './Form.vue';
import Seguimientos from '@/components/Seguimientos.vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

const propsDef = defineProps([
  'seguimiento_estudiante',
  'estados',
  'usuarios',
  'history_seguimiento_estudiante'
]);

const verifyType = (abandonos_deserciones, id) => abandonos_deserciones.findIndex(dropoff => dropoff.seguimiento_tipo.id == id)

const isJefe = user.rol === 'JEFE';
const isOwner = propsDef.seguimiento_estudiante.usuario_id == user.id;
const alreadyDone = false //propsDef.seguimiento_estudiante.seguimiento_estado_id == 5;
const abandono = verifyType(propsDef.seguimiento_estudiante.abandonos_deserciones, 1) >= 0
const desercion = verifyType(propsDef.seguimiento_estudiante.abandonos_deserciones, 2) >= 0

// permisos
const canEdit = (isJefe || isOwner) && ! alreadyDone;

const recordTitle = (propsDef.seguimiento_estudiante?.seguimiento_tipo?.nombre?.toLowerCase() === 'deserción') ? 'Deserción' : 'Abandono';

console.log(propsDef.seguimiento_estudiante)

</script>

<template>
  <div class="p-6 space-y-6">

    <!--div v-if="$page.props.flash.error" class="bg-red-500 text-white p-2">
      {{ $page.props.flash.error }}
    </div-->

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-xl font-bold">
          Editar {{ recordTitle }}
        </h1>
        <p class="text-sm text-gray-500">
          {{ seguimiento_estudiante.estudiante.nombres }} {{ seguimiento_estudiante.estudiante.apellidos }}
        </p>
      </div>

      <Link
        href="/dropoffs"
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
            Información
          </h2>
        </div>

        <Form
          :seguimiento_estudiante="seguimiento_estudiante"
          :estados="estados"
          :usuarios="usuarios"
        />

        <!-- MENSAJE SI NO PUEDE EDITAR -->
        <div
          v-if="!canEdit"
          class="mt-4 text-sm text-red-500"
        >
          No tienes permisos para editar.
        </div>

        <div class="mb-4 border-b pb-2 mt-2">
          <h2 class="font-semibold text-gray-700">
            Historial
          </h2>
        </div>

        <div class="mt-4 space-y-4">
          <div v-if="history_seguimiento_estudiante.length === 0" class="text-sm text-gray-400">
            No hay historial disponible.
          </div>

          <div v-for="(item, index) in history_seguimiento_estudiante" :key="index" class="flex gap-3">

            <!-- Línea / punto -->
            <div class="flex flex-col items-center">
              <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
              <div v-if="index !== history_seguimiento_estudiante.length - 1" class="w-px flex-1 bg-gray-300"></div>
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

          <div><strong class="mr-2">Estado:</strong> {{ seguimiento_estudiante.seguimiento_estado?.nombre }}</div>
          <div><strong class="mr-2">Tipo:</strong> 
            <span
              class="px-2 py-1 rounded text-xs bg-orange-500"
              v-if="abandono"
            >
              Abandono
            </span>
            <span
              class="px-2 py-1 rounded text-xs bg-teal-500"
              v-if="desercion"
            >
              Deserción
            </span>
          </div>
          <div><strong class="mr-2">Asesor:</strong> {{ seguimiento_estudiante.usuario?.nombre }}</div>

          <div v-if="seguimiento_estudiante.ultimo_contacto">
            <strong>Último contacto:</strong><br>
            {{ new Date(seguimiento_estudiante.ultimo_contacto).toLocaleString() }}
          </div>

        </div>

        <!-- SEGUIMIENTOS -->
        <div class="bg-white p-4 rounded shadow" v-if="! alreadyDone" >

          <Seguimientos :lead="seguimiento_estudiante" :dropoff="true" />

        </div>

      </div>

    </div>

  </div>
</template>