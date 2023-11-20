<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useLayout } from "@/Layouts/composables/layout";
// import { useRouter } from 'vue-router';
import { router, useForm, usePage } from "@inertiajs/vue3";
import { asset } from "../Libs/supports";

const { layoutConfig, onMenuToggle } = useLayout();

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
// const router = useRouter();
const menuProfileToggle = ref()

const page = usePage()
const auth = computed(() => page.props?.auth)

onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});

const logoUrl = computed(() => {
    return asset(`layout/images/${layoutConfig.darkTheme.value ? "logo-white" : "logo-dark"
        }.svg`);
});

const onTopBarMenuButton = () => {
    topbarMenuActive.value = !topbarMenuActive.value;
};
const onSettingsClick = () => {
    topbarMenuActive.value = false;
    router.push("/documentation");
};
const topbarMenuClasses = computed(() => {
    return {
        "layout-topbar-menu-mobile-active": topbarMenuActive.value,
    };
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                topbarMenuActive.value = false;
            }
        };
        document.addEventListener("click", outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener("click", outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    if (!topbarMenuActive.value) return;

    const sidebarEl = document.querySelector(".layout-topbar-menu");
    const topbarEl = document.querySelector(".layout-topbar-menu-button");

    return !(
        sidebarEl.isSameNode(event.target) ||
        sidebarEl.contains(event.target) ||
        topbarEl.isSameNode(event.target) ||
        topbarEl.contains(event.target)
    );
};

const logoutDialog = ref(false)
const logoutForm = useForm({})
const logoutHandle = () => {
    logoutForm.post(route('auth.logout'))
}

const menuProfile = ref([
    {
        separator: true
    },
    {
        label: 'Profile',
        items: [
            {
                label: 'Settings',
                icon: 'pi pi-cog',
                shortcut: '⌘+O'
            },
            {
                label: 'Messages',
                icon: 'pi pi-inbox',
                badge: 2
            },
            {
                label: 'Logout',
                icon: 'pi pi-sign-out',
                shortcut: '⌘+Q',
                command: () => logoutDialog.value = true
            }
        ]
    },
    {
        separator: true
    }
])


</script>

<template>
    <div class="layout-topbar">
        <InertiaLink href="/" class="layout-topbar-logo">
            <img :src="logoUrl" alt="logo" />
            <span>MurahMewah</span>
        </InertiaLink>

        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
            <i class="pi pi-bars"></i>
        </button>

        <button class="p-link layout-topbar-menu-button layout-topbar-button" @click="onTopBarMenuButton()">
            <i class="pi pi-ellipsis-v"></i>
        </button>

        <div class="layout-topbar-menu" :class="topbarMenuClasses">

            <button v-ripple @click="menuProfileToggle?.toggle($event)"
                class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-3 text-color hover:surface-200 border-noround">
                <Avatar :image="auth?.avatar_url" class="mr-2" shape="circle" />
                <span class="inline-flex flex-column">
                    <span class="font-bold" v-html="auth?.name"></span>
                    <span class="text-sm" v-html="auth?.s_role?.string"></span>
                </span>
            </button>
            <Menu :model="menuProfile" class="w-full md:w-15rem" ref="menuProfileToggle" :popup="true">
                <template #start>
                    <span class="inline-flex align-items-center gap-1 px-2 py-2">
                        <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-2rem">
                            <path d="..." fill="var(--primary-color)" />
                            <path d="..." fill="var(--text-color)" />
                        </svg>
                        <span class="font-medium text-xl font-semibold">Murah<span class="text-primary">Mewah</span></span>
                    </span>
                </template>
                <template #submenuheader="{ item }">
                    <span class="text-primary font-bold">{{ item.label }}</span>
                </template>
                <template #item="{ item, props }">
                    <a v-ripple class="flex align-items-center" v-bind="props.action">
                        <span :class="item.icon" />
                        <span class="ml-2">{{ item.label }}</span>
                        <Badge v-if="item.badge" class="ml-auto" :value="item.badge" />
                        <span v-if="item.shortcut"
                            class="ml-auto border-1 surface-border border-round surface-100 text-xs p-1">{{ item.shortcut
                            }}</span>
                    </a>
                </template>
                <template #end>
                    <button v-ripple
                        class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-3 text-color hover:surface-200 border-noround">
                        <Avatar :image="auth?.avatar_url" class="mr-2" shape="circle" />
                        <span class="inline-flex flex-column">
                            <span class="font-bold" v-html="auth?.name"></span>
                            <span class="text-sm" v-html="auth?.s_role?.string"></span>
                        </span>
                    </button>
                </template>
            </Menu>
            <Dialog header="Konfirmasi" v-model:visible="logoutDialog" :style="{ width: '350px' }" :modal="true">
                <div class="flex align-items-center justify-content-center">
                    <i class="pi pi-exclamation-triangle mr-3" style="font-size: 1rem" />
                    <span>Yakin ingin keluar?</span>
                </div>
                <template #footer>
                    <Button label="Batal" icon="pi pi-times" @click="logoutDialog = false" class="p-button-text" />
                    <Button label="Keluar" icon="pi pi-check" :loading="logoutForm.processing" @click="logoutHandle"
                        class="p-button-text" />
                </template>
            </Dialog>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>
