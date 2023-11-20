<template>
    <Button :class="btnClass ?? [classDefault, `p-button-${btnColor}`]" :label="label" :loading="loading">
        <template #icon>
            <Icon :icon="icon" :class="{ 'mr-2': label }" />
        </template>
    </Button>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
defineProps({
    icon: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: ''
    },
    classDefault: {
        type: String,
        default: 'p-button-rounded'
    },
    btnColor: {
        type: String as PropType<"primary" | "warning" | "danger" | "success" | "secondary">,
        default: 'primary'
    },
    btnClass: {
        type: String,
        default: null
    },
    loading: {
        type: Boolean,
        default: false
    }
})
</script>

<style lang="scss" scoped></style>
