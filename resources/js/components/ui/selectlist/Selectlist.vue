<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { useVModel } from "@vueuse/core"
import { cn } from "@/lib/utils"
import { computed } from "vue"

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes["class"]
  options: any[]
  key?: string
  value?: string
  colors?: boolean
}>()

const emits = defineEmits<{
  (e: "update:modelValue", payload: string | number): void
}>()

const modelValue = useVModel(props, "modelValue", emits, {
  passive: true,
  defaultValue: props.defaultValue,
})

const key = props.key || 'id'
const value = props.value || 'value'
const colors = props.colors || false

const colorMap: Record<string, string> = {
  red: 'bg-red-300 text-red-800',
  green: 'bg-green-300 text-green-800',
  emerald: 'bg-emerald-300 text-emerald-800',
  cyan: 'bg-cyan-300 text-cyan-800',
  yellow: 'bg-yellow-300 text-yellow-800',
  orange: 'bg-orange-300 text-orange-800',
  grey: 'bg-grey-300 text-grey-800',
}

const selectedOption = computed(() => props.options.find(opt => opt[key] == modelValue.value))

const selectColorClass = computed(() => {
  if (!colors || !selectedOption.value) return ''
  return  colorMap[selectedOption.value.color] || ''
})

</script>

<template>
  <select
    v-model="modelValue"
    :style="colors && selectedOption?.color 
      ? { backgroundColor: '#' + selectedOption.color } 
      : {}"
    :class="cn(
      'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      selectColorClass,
      props.class,
    )"
  >
    <option
      v-for="option in options"
      :key="option[key]"
      :value="option[key]"
      :style="colors ? { backgroundColor: '#ffffff', color: '#000000'} : null"
    >
      {{ option[value] }}
    </option>
  </select>
</template>