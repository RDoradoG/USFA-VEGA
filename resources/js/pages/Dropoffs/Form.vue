```vue
<script setup>
import Selectlist from '@/components/ui/selectlist/Selectlist.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = page.props.auth.user;
const error = computed(() => page.props.flash.error);

const propsDef = defineProps({
    seguimiento_estudiante: Object,
    estados: Array,
    usuarios: Array,
});

const seguimiento = propsDef.seguimiento_estudiante;
const isJefe = user.rol === 'JEFE';
const alreadyDone = seguimiento?.seguimiento_estado_id == 5;
const isOwner =
    seguimiento?.usuario_id == user.id ||
    seguimiento?.usuario_id === null;
const canChangeState =
    isJefe ||
    (isOwner && seguimiento !== undefined);
const canEdit = canChangeState && !alreadyDone;

const form = useForm({
    seguimiento_estado_id: seguimiento.seguimiento_estado_id || '',
    usuario_id: seguimiento.usuario_id || '',
    motivo: seguimiento.motivo || '',
    observaciones: seguimiento.observaciones || ''
});

const submit = () => {
    if (!canEdit) return;

    form.put(`/dropoffs/${seguimiento.id}`);
};
</script>

<template>
    <div v-if="error" class="bg-red-500 text-white p-3 mb-6 rounded-lg shadow-sm">
        {{ error }}
    </div>


    <form @submit.prevent="submit" class="space-y-6">
        <!-- ========================================================= -->
        <!-- INFORMACIÓN DEL ESTUDIANTE -->
        <!-- ========================================================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombres -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Nombres
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ seguimiento?.estudiante?.nombres || '-' }}
                        </p>
                    </div>

                    <!-- Apellidos -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Apellidos
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-800">
                            {{ seguimiento?.estudiante?.apellidos || '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- INFORMACIÓN DEL SEGUIMIENTO -->
        <!-- ========================================================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Abandonos y Deserciones
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Información y asignación de cada Abandonos y Deserciones
                </p>
            </div>

            <div class="p-6 space-y-6">

                <div v-for="(dropoff, index) in seguimiento?.abandonos_deserciones"
                    :key="dropoff.id"
                    class="border border-gray-200 rounded-lg p-5">


                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-semibold text-gray-700">
                            Registro #{{ index + 1 }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- TIPO -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Tipo
                            </p>
                            <div class="mt-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium"
                                    :class="dropoff.seguimiento_tipo_id == 1
                                        ? 'bg-orange-100 text-orange-700'
                                        : 'bg-red-100 text-red-700'">
                                    {{ dropoff.seguimiento_tipo?.nombre || '-' }}
                                </span>
                            </div>
                        </div>
                        <!-- CARRERA -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Carrera
                            </p>
                            <div class="mt-4">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ dropoff.carrera?.nombre || '-' }}
                                </span>
                            </div>
                        </div>
                        <!-- SEDE -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Sede
                            </p>
                            <div class="mt-4">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ dropoff.sede?.nombre || '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- ========================================================= -->
                    <!-- INFORMACIÓN DE DESERCIÓN -->
                    <!-- SOLO SE MUESTRA PARA DESERCIÓN -->
                    <!-- ========================================================= -->
                    <div v-if="dropoff.seguimiento_tipo_id == 2">
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">
                                    Información de deserción
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Situación académica del estudiante
                                </p>
                            </div>
                        </div>
                        <!-- Si existen registros -->
                        <div v-if="dropoff.seguimiento_deserciones?.length" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-600">
                                            Nombre
                                        </th>

                                        <th class="text-left py-3 px-4 font-semibold text-gray-600">
                                            Módulo
                                        </th>

                                        <th class="text-center py-3 px-4 font-semibold text-gray-600">
                                            Nota
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="desercion in dropoff.seguimiento_deserciones"
                                        :key="desercion.id"
                                        class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">
                                        <!-- Nombre -->
                                        <td class="py-3 px-4">
                                            <span class="font-medium text-gray-800">
                                                {{ desercion.desercion_tipo?.nombre || '-' }}
                                            </span>
                                        </td>
                                        <!-- Materia -->
                                        <td class="py-3 px-4 text-gray-700">
                                            {{ desercion.materia || '-' }}
                                        </td>
                                        <!-- Nota -->
                                        <td class="py-3 px-4 text-center">
                                            <span v-if="desercion.nota !== null && desercion.nota !== ''"
                                                class="inline-flex items-center justify-center
                                                        min-w-[40px] px-2 py-1 rounded-md
                                                        bg-gray-100 text-gray-700 font-medium">
                                                {{ desercion.nota }}
                                            </span>

                                            <span v-else class="text-gray-400">
                                                -
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sin registros -->
                        <div v-else class="text-center py-8 text-gray-500">
                            <p class="text-sm">
                                No existen registros de materias para esta deserción.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Seguimiento
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Información y asignación del seguimiento
                </p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Estado
                        </label>
                        <div class="mt-2">
                            <Selectlist v-model="form.seguimiento_estado_id"
                                :colors="true"
                                :options="estados"
                                :key="'id'"
                                :value="'nombre'"
                                :disabled="!canEdit"
                                :class="{'border-red-500': form.errors.seguimiento_estado_id}"
                            />
                            <p v-if="form.errors.seguimiento_estado_id" class="text-red-500 text-sm mt-1">
                                {{ form.errors.seguimiento_estado_id }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Asesor
                        </label>
                        <div class="mt-2">
                            <Selectlist v-model="form.usuario_id"
                                :options="usuarios"
                                :key="'id'"
                                :value="'nombre'"
                                :disabled="!isJefe || alreadyDone"
                                :class="{'border-red-500': form.errors.usuario_id}"
                            />
                            <p v-if="form.errors.usuario_id" class="text-red-500 text-sm mt-1">
                                {{ form.errors.usuario_id }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Motivo y observaciones
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Registra información relacionada con el seguimiento
                </p>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                        Motivo
                    </label>
                    <Input v-model="form.motivo"
                        placeholder="Ingrese el motivo..."
                        :disabled="!canEdit"
                        :class="{'border-red-500': form.errors.motivo}"
                    />
                    <p v-if="form.errors.motivo" class="text-red-500 text-sm mt-1">
                        {{ form.errors.motivo }}
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                        Observaciones
                    </label>
                    <Textarea v-model="form.observaciones"
                        placeholder="Ingrese observaciones..."
                        :disabled="!canEdit"
                        :class="{'border-red-500': form.errors.observaciones}"
                    />
                    <p v-if="form.errors.observaciones" class="text-red-500 text-sm mt-1">
                        {{ form.errors.observaciones }}
                    </p>
                </div>
            </div>
        </div>
        <div v-if="canEdit">
            <button type="submit"
                :disabled="form.processing"
                class="bg-green-500 text-white px-6 py-2 rounded">
                {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
            </button>
        </div>
    </form>
</template>