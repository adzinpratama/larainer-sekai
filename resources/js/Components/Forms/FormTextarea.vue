<template>
    <div :class="['field', col]">
        <label v-if="label">{{ label }}<code v-if="required" class="text-red-500">*</code></label>
        <Textarea :rows="rows" :modelValue="modelValue" @input="emit('update:modelValue', $event.target.value)"
            :class="{ 'p-invalid': errors }" :disabled="disabled" :required="required" />
        <div v-if="errors" class="p-error">{{ errors }}</div>
    </div>
</template>

<script setup>

defineProps({
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
    rows: {
        type: Number,
        default: 4
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
