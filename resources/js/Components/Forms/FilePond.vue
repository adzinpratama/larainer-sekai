<script setup>
import { onMounted } from "vue";
import { create, registerPlugin } from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";

import { ref } from "@vue/reactivity";

const props = defineProps({
    id: {
        type: String,
        default: "filepod_" + Math.floor(Math.random() * 100) + 1,
    },
    labelIdle: {
        default: "click atau letakan file disini...",
        type: String,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    acceptedFileTypes: {
        type: Array,
        default: ["image/*"],
    },
    maxFileSize: {
        type: String,
        default: "5mb",
    },
});

const emit = defineEmits(["update:modelValue"]);
const pondRef = ref();

registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation
);

onMounted(() => {
    const fileInput = document.querySelector("#" + props.id);

    const pond = create(fileInput, {
        storeAsFile: true,
        dropOnPage: true,
        allowMultiple: props.multiple,
        credits: null,
        labelIdle: props.labelIdle,
        labelFileTypeNotAllowed: "Jenis File Tidak didukung",
        acceptedFileTypes: props.acceptedFileTypes,
        maxFileSize: props.maxFileSize,
    });

    pond.on("updatefiles", (files) => {
        let value = files.map(function (filepond) {
            return filepond.file;
        })
        if (!props.multiple) value = value?.[0]
        emit("update:modelValue", value);
    });
    pondRef.value = pond;
});

const removeFiles = () => pondRef.value?.removeFiles();

defineExpose({ removeFiles });
</script>

<template>
    <input :id="id" type="file" />
</template>

<style scoped></style>
