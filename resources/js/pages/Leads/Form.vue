<script setup>
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import Datepicker from '@/components/ui/datepicker/Datepicker.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useForm, usePage  } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth.user;

const propsDef = defineProps({
  lead: Object,
  sedes: Array,
  carreras: Array,
  horarios: Array,
  estados: Array,
  usuarios: Array,
  fuentes: Array,
});

const isJefe = user.rol === 'JEFE';
const isOwner = propsDef.lead?.usuario_id === user.id || propsDef.lead === undefined;
const alreadyDone = propsDef.lead?.estado_id == 6

const canChangeState = user.rol === 'JEFE' || (isOwner && propsDef.lead !== undefined)

const canEdit = (isJefe || isOwner) && ! alreadyDone;
//const canCreate = isJefe;

const genreOptions = [
  {id: 'Masculino', value: 'Masculino'},
  {id: 'Femenino', value: 'Femenino'}
]

const interests = [
  {id: 'Alto', value: 'Alto'},
  {id: 'Medio', value: 'Medio'},
  {id: 'Bajo', value: 'Bajo'}
]

const form = useForm({
  nombre: propsDef.lead?.nombre || '',
  apellido_paterno: propsDef.lead?.apellido_paterno || '',
  apellido_materno: propsDef.lead?.apellido_materno || '',
  celular: propsDef.lead?.celular || '',
  genero: propsDef.lead?.genero || '',
  ciudad: propsDef.lead?.ciudad || '',

  sede_id: propsDef.lead?.sede_id || '',
  carrera_id: propsDef.lead?.carrera_id || '',
  horario_id: propsDef.lead?.horario_id || '',

  estado_id: propsDef.lead?.estado_id || '1',
  usuario_id: propsDef.lead?.usuario_id || '',
  fuente_id: propsDef.lead?.fuente_id || '',

  interes_nivel: propsDef.lead?.interes_nivel || 'Bajo',

  fecha_registro: propsDef.lead?.fecha_registro || '',
  ultimo_contacto: propsDef.lead?.ultimo_contacto || '',

  observaciones: propsDef.lead?.observaciones || '',
});

const submit = () => {
    if (!canEdit) return;

    if (propsDef.lead) {
        form.put(`/leads/${propsDef.lead.id}`);
    } else {
        form.post('/leads');
    }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-6">

    <!-- DATOS BASICOS -->
    <div class="grid grid-cols-3 gap-4">
      <div> 
        <Label required>Nombre</Label>
        <Input v-model="form.nombre" placeholder="Nombre" :disabled="!canEdit" :class="{'border-red-500': form.errors.nombre}"/>
        <p v-if="form.errors.nombre" class="text-red-500 text-sm mt-1">{{ form.errors.nombre }}</p>
      </div>

      <div>
        <Label required>Apellido Paterno</Label>
        <Input v-model="form.apellido_paterno" placeholder="Apellido Paterno" :disabled="!canEdit" :class="{'border-red-500': form.errors.apellido_paterno}" />
        <p v-if="form.errors.apellido_paterno" class="text-red-500 text-sm mt-1">{{ form.errors.apellido_paterno }}</p>
      </div>

      <div>
        <Label required>Apellido Materno</Label>
        <Input v-model="form.apellido_materno" placeholder="Apellido Materno" :disabled="!canEdit" :class="{'border-red-500': form.errors.apellido_materno}" />
        <p v-if="form.errors.apellido_materno" class="text-red-500 text-sm mt-1">{{ form.errors.apellido_materno }}</p>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <Label required>Celular</Label>
        <Input v-model="form.celular" placeholder="########" :disabled="!canEdit" :class="{'border-red-500': form.errors.celular}" />
        <p v-if="form.errors.celular" class="text-red-500 text-sm mt-1">{{ form.errors.celular }}</p>
      </div>

      <div>
        <Label>Género</Label>
        <Selectlist v-model="form.genero" :options="genreOptions" :disabled="!canEdit" />
      </div>

      <div>
        <Label>Ciudad</Label>
        <Input v-model="form.ciudad" placeholder="Ciudad" :disabled="!canEdit" />
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <Label required>Sede</Label>
        <Selectlist v-model="form.sede_id" :options="sedes" :key="'id'" :value="'nombre'" :disabled="!canEdit" :class="{'border-red-500': form.errors.sede_id}" />
        <p v-if="form.errors.sede_id" class="text-red-500 text-sm mt-1">{{ form.errors.sede_id }}</p>
      </div>

      <div>
        <Label required>Carrera</Label>
        <Selectlist v-model="form.carrera_id" :options="carreras" :key="'id'" :value="'nombre'" :disabled="!canEdit" />
      </div>

      <div>
        <Label required>Horario</Label>
        <Selectlist v-model="form.horario_id" :options="horarios" :key="'id'" :value="'nombre'" :disabled="!canEdit" />
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <Label required>Estado</Label>
        <Selectlist 
          v-model="form.estado_id"
          :colors="true"
          :options="estados"
          :key="'id'"
          :value="'nombre'"
          :disabled="!canChangeState || alreadyDone"
          :class="{'border-red-500': form.errors.estado_id}" 
        />
        <p v-if="form.errors.estado_id" class="text-red-500 text-sm mt-1">{{ form.errors.estado_id }}</p>
      </div>

      <div>
        <Label>Asesor</Label>
        <Selectlist v-model="form.usuario_id" :options="usuarios" :key="'id'" :value="'nombre'" :disabled="!isJefe || alreadyDone" />
      </div>

      <div>
        <Label required>Fuente</Label>
        <Selectlist v-model="form.fuente_id" :options="fuentes" :key="'id'" :value="'nombre'" :disabled="!canEdit" :class="{'border-red-500': form.errors.fuente_id}" />
        <p v-if="form.errors.fuente_id" class="text-red-500 text-sm mt-1">{{ form.errors.fuente_id }}</p>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <Label required>Nivel de interés</Label>
        <Selectlist v-model="form.interes_nivel" :options="interests" :disabled="!canEdit" :class="{'border-red-500': form.errors.interes_nivel}" />
        <p v-if="form.errors.interes_nivel" class="text-red-500 text-sm mt-1">{{ form.errors.interes_nivel }}</p>
      </div>

      <div>
        <Label>Fecha registro</Label>
        <Datepicker v-model="form.fecha_registro" :disabled="true" />
      </div>

      <div>
        <Label>Último contacto</Label>
        <Datepicker v-model="form.ultimo_contacto" :disabled="!canEdit" />
      </div>
    </div>

    <!-- OBSERVACIONES -->
    <div>
      <Label>Observaciones</Label>
      <Textarea v-model="form.observaciones" :disabled="!canEdit" />
    </div>

    <!-- BOTON -->
    <div>
      <button v-if="canEdit" class="bg-green-500 text-white px-6 py-2 rounded">
        Guardar
      </button>
    </div>

  </form>
</template>