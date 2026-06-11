<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';

const props = defineProps<{
    active: boolean;
    number: number;
}>();

const classNames = computed(() => props.active ? 'z-10 bg-blue-usfa text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:outline-offset-0');

</script>
<template>
    <span :class="
        cn(
            'cursor-pointer inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20',
            classNames
        )
    ">
        {{ number }}
    </span>
</template>