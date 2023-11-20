<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from '@vue/reactivity';

import AppMenuItem from './AppMenuItem.vue';

const page = usePage()
const model = computed(() => page.props?.mainMenus)
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
