<template>
    <AppLayout>
        <div class="grid">
            <div class="col-6 md:col-8 lg:col-10">
                <Breadcrumb :home="breadcrumbHome" :model="breadcrumbItems">
                </Breadcrumb>
            </div>
            <div class="col-2 md:col-4 lg:col-2 text-right">
                <Button label="Tambah Merchant" @click="addtoggle = true" v-if="!addtoggle">
                    <template #icon>
                        <Icon icon="bi:plus" />
                    </template>
                </Button>
                <FButton v-else icon="bi:x" btnColor="secondary" @click="() => {
                    forms.clearErrors()
                    forms.reset()
                    addtoggle = false
                }" />

            </div>
            <Transition name="slide-fadeYt">
                <div class="col-12" v-if="addtoggle">
                    <form @submit.prevent="formHandle(forms, {
                        routeName: 'merchant.content.store',
                        onSuccess: (e) => log(e)
                    })" class="card">
                        <h5>Form Merchant</h5>
                        <div class="p-fluid formgrid grid">
                            <FormInput v-model="forms.name" label="Nama" :errors="forms.errors.name" required />
                            <FormTextarea v-model="forms.address" :errors="forms.errors.address" label="Alamat" required />
                            <FormTextarea label="Deskripsi" v-model="forms.description"
                                :errors="forms.errors?.description" />

                            <div class="field col-12 md:col-6">
                                <label>Avatar</label>
                                <FilePond ref="avatarRef" v-model="forms.avatar" />
                            </div>
                            <div class="field col-12 md:col-6">
                                <label>Banner</label>
                                <FilePond ref="bannerRef" id="filepon-banner" v-model="forms.banner" />
                            </div>
                        </div>
                        <div class="flex justify-content-end w-100">
                            <Button icon="pi pi-save" type="submit" label="Simpan Perubahan" :loading="forms.processing" />
                        </div>
                    </form>
                </div>
            </Transition>
            <div class="col-12" v-if="!addtoggle">
                <div class="card">
                    <DataTable :value="merchants?.data" responsiveLayout="scroll">
                        <Column header="Nama">
                            <template #body="{ data }">
                                <Avatar :alt="data.name" v-bind:image="data.avatar_url" width="32"
                                    style="vertical-align: middle" shape="circle" />
                                <span style="margin-left: 0.5em; vertical-align: middle" class="image-text">{{
                                    data.name }}</span>
                            </template>
                        </Column>
                        <Column header="Banner">
                            <template #body="{ data }">
                                <img v-bind:src="data.banner_url" width="150" class="shadow-2" alt="banner" />
                            </template>
                        </Column>
                        <Column header="Alamat" field="address"></Column>
                        <Column header="">
                            <template #body="{ data }">
                                <FButton icon="material-symbols:edit-square-outline" label="Edit"
                                    @click="editHandle(forms, data, ['avatar', 'banner'], (e) => addtoggle = true)" />
                                <ConfirmPopup group="templating"></ConfirmPopup>
                                <FButton icon="bi:trash" tooltip="Hapus merchant" btnColor="danger" :loading="data?.trash"
                                    @click="removeHandle" />
                            </template>
                        </Column>
                    </DataTable>

                    <ConfirmPopup id="confirm" aria-label="popup" />

                    <Button @click="openPopup($event)" label="Confirm" id="confirmButton" :aria-expanded="isVisible"
                        :aria-controls="isVisible ? 'confirm' : null" />

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "@vue/reactivity";
import AppLayout from "../../../../../../resources/js/Layouts/AppLayout.vue"
import FilePond from "../../../../../../resources/js/Components/Forms/FilePond.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import FormInput from "../../../../../../resources/js/Components/Forms/FormInput.vue";
import FormTextarea from "../../../../../../resources/js/Components/Forms/FormTextarea.vue";
import { formHandle, editHandle } from "../../../../../../resources/js/Libs/handle"
import { asset } from "../../../../../../resources/js/Libs/supports";
import FButton from "../../../../../../resources/js/Components/Forms/FButton.vue";
import { useConfirm } from "primevue/useconfirm";

const breadcrumbHome = ref({ icon: 'pi pi-home', });
const breadcrumbItems = ref([{ label: 'Merchant' }]);

const avatarRef = ref()
const bannerRef = ref()
const page = usePage()
const merchants = computed(() => page.props?.merchants)

const addtoggle = ref(false)
const forms = useForm({
    id: null,
    name: null,
    address: null,
    avatar: null,
    banner: null,
    description: null
})


const removeHandle = (ev) => {
    const confirm = useConfirm()
    confirm.require({
        message: 'yakin',
        accept: () => alert('oke'),
        group: 'templating'
    })
}

const isVisible = ref(false);
const confirm = useConfirm()
const openPopup = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        onShow: () => {
            isVisible.value = true;
        },
        onHide: () => {
            isVisible.value = false;
        }
    });
}

const log = (e) => console.log(e);
</script>

<style lang="scss" scoped></style>
