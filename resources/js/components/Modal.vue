<script setup>
defineProps({
  show: Boolean
});

const emit = defineEmits(['close']);
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">

    <!-- BACKDROP -->
    <div
      class="absolute inset-0 bg-black/50"
      @click="$emit('close')"
    ></div>

    <!-- MODAL -->
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6 z-10">
      <slot />
    </div>

  </div>
</template>