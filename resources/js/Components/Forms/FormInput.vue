<template>
    <div :class="['field', col]">
        <label v-if="label">{{ label }}<code v-if="required" class="text-red-500">*</code></label>
        <InputText :type="type" :modelValue="modelValue" @input="emit('update:modelValue', $event.target.value)"
            @keyup="emit('keyup', $event)" :class="{ 'p-invalid': errors }" :disabled="disabled" :required="required" />
        <div v-if="errors" class="p-error">{{ errors }}</div>
    </div>
</template>

<script setup lang="ts">
import { PropType } from "vue";

defineProps({
    type: {
        type: String as PropType<"number" | "text" | "date">,
        default: "text"
    },
    label: {
        type: String,
        default: null
    },
    modelValue: String,
    errors: {
        type: String,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    },
    col: {
        type: String,
        default: 'col-12'
    },
    required: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['modelValue', 'keyup'])
</script>

<style lang="scss" scoped></style>
