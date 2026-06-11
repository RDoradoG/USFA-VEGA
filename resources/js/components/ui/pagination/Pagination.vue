<script setup lang="ts">

import { type MetaData } from '@/types';
import { computed } from 'vue';
import PageCell from './PageCell.vue';

interface Props {
    meta: MetaData;
}

const props = defineProps<Props>();

const initValue = computed(() => {
    let meta = props.meta ?? {current_page: 0, per_page: 0}
    return ((meta.current_page - 1) * meta.per_page) + 1
});

const endValue = computed(() => {
    let meta = props.meta ?? {per_page: 0, total: 0}
    let endValue = initValue.value + meta.per_page - 1
    return endValue < meta.total ? endValue : meta.total
});

const current_page = computed(() => {
    let meta = props.meta ?? {current_page: 0}
    return meta.current_page
});

const total = computed(() => {
    let meta = props.meta ?? {total: 0}
    return meta.total
});

const last_page = computed(() => {
    let meta = props.meta ?? {last_page: 0}
    return meta.last_page
});

</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <span class="cursor-pointer relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Anterior</span>
            <span class="cursor-pointer relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Siguiente</span>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ initValue }}</span>
                    to
                    <span class="font-medium">{{ endValue }}</span>
                    of
                    <span class="font-medium">{{ total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                    <span v-if="current_page > 1" @click="$emit('setPage', (current_page - 1))" class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Anterior</span>
                        <
                    </span>
                    <span v-if="current_page <= 1" class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Anterior</span>
                        <
                    </span>

                    <PageCell :active="current_page == 1" :number="1" @click="$emit('setPage', 1)" />
                    <PageCell :active="current_page == 2" :number="2" v-if="last_page > 1" @click="$emit('setPage', 2)" />
                    <PageCell :active="current_page == 3" :number="3" v-if="last_page > 2" @click="$emit('setPage', 3)" />

                    <PageCell :active="current_page == 4" :number="4" v-if="last_page > 6 && last_page <= 9" @click="$emit('setPage', 4)" />
                    <PageCell :active="current_page == 5" :number="5" v-if="last_page > 7 && last_page <= 9" @click="$emit('setPage', 5)" />
                    <PageCell :active="current_page == 6" :number="6" v-if="last_page > 8 && last_page <= 9" @click="$emit('setPage', 6)" />
                    
                    <span v-if="last_page > 9 && current_page != 4" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 ring-inset focus:outline-offset-0">...</span>
                    <PageCell :active="true" :number="current_page" v-if="last_page > 9 && current_page > 3 && current_page < (last_page - 2)" />
                    <span v-if="last_page > 9 && current_page < (last_page - 3)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 ring-inset focus:outline-offset-0">...</span>
                    
                    <PageCell :active="current_page == (last_page - 2)" :number="last_page - 2" v-if="last_page > 5" @click="$emit('setPage', (last_page - 2))" />
                    <PageCell :active="current_page == (last_page - 1)" :number="last_page - 1" v-if="last_page > 4" @click="$emit('setPage', (last_page - 1))" />
                    <PageCell :active="current_page == last_page" :number="last_page" v-if="last_page > 3" @click="$emit('setPage', last_page)" />
                    
                    <span v-if="current_page < last_page" @click="$emit('setPage', (current_page + 1))" class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Siguiente</span>
                        >
                    </span>
                    <span v-if="current_page >= last_page" class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Siguiente</span>
                        >
                    </span>
                </nav>
            </div>
        </div>
    </div>
</template>