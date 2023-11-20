<script setup>
import { ref, onBeforeMount, watch, computed } from "vue";
// import { useRoute } from 'vue-router';
import { useLayout } from "@/Layouts/composables/layout";
import { usePage } from "@inertiajs/vue3";

const { layoutConfig, layoutState, setActiveMenuItem, onMenuToggle } =
    useLayout();

const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
    index: {
        type: Number,
        default: 0,
    },
    root: {
        type: Boolean,
        default: true,
    },
    parentItemKey: {
        type: String,
        default: null,
    },
});

const isActiveMenu = ref(false);
const itemKey = ref(null);
const page = usePage()
const s_route = computed(() => page.props?.s_route)

onBeforeMount(() => {
    itemKey.value = props.parentItemKey
        ? props.parentItemKey + "-" + props.index
        : String(props.index);

    const activeItem = layoutState.activeMenuItem;

    isActiveMenu.value =
        activeItem === itemKey.value || activeItem
            ? activeItem.startsWith(itemKey.value + "-")
            : false;
});

watch(
    () => layoutConfig.activeMenuItem.value,
    (newVal) => {
        isActiveMenu.value =
            newVal === itemKey.value || newVal.startsWith(itemKey.value + "-");
    }
);
const itemClick = (event, item) => {
    if (item.disabled) {
        event.preventDefault();
        return;
    }

    const { overlayMenuActive, staticMenuMobileActive } = layoutState;

    if (
        (item.to || item.url) &&
        (staticMenuMobileActive.value || overlayMenuActive.value)
    ) {
        onMenuToggle();
    }

    if (item.command) {
        item.command({ originalEvent: event, item: item });
    }

    const foundItemKey = item.items
        ? isActiveMenu.value
            ? props.parentItemKey
            : itemKey
        : itemKey.value;

    setActiveMenuItem(foundItemKey);
};

const checkActiveRoute = (item) => {
    return s_route.value?.uri === item.to || item?.active;
};
</script>

<template>
    <li :class="{
        'layout-root-menuitem': root,
        'active-menuitem': isActiveMenu,
    }">
        <div v-if="root && item.visible !== false" class="layout-menuitem-root-text">
            {{ item.label }}
        </div>
        <a v-if="(!item.to || item.items) && item.visible !== false" :href="item.url"
            @click="itemClick($event, item, index)" :class="item.class" :target="item.target" tabindex="0">
            <Icon :icon="item.icon" class="layout-menuitem-icon" />
            <span class="layout-menuitem-text">{{ item.label }}</span>
            <i class="pi pi-fw pi-angle-down layout-submenu-toggler" v-if="item.items"></i>
        </a>
        <InertiaLink v-if="item.to && !item.items && item.visible !== false" @click="itemClick($event, item, index)"
            :class="[item.class, { 'active-route': checkActiveRoute(item) }]" tabindex="0" :href="item.to">
            <Icon :icon="item.icon" class="layout-menuitem-icon" />
            <span class="layout-menuitem-text">{{ item.label }}</span>
            <i class="pi pi-fw pi-angle-down layout-submenu-toggler" v-if="item.items"></i>
        </InertiaLink>
        <Transition v-if="item.items && item.visible !== false" name="layout-submenu">
            <ul v-show="root ? true : isActiveMenu" class="layout-submenu">
                <app-menu-item v-for="(child, i) in item.items" :key="child" :index="i" :item="child"
                    :parentItemKey="itemKey" :root="false"></app-menu-item>
            </ul>
        </Transition>
    </li>
</template>

<style lang="scss" scoped></style>
