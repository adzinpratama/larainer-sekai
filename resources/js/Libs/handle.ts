import { toast } from "vue3-toastify";
import { useConfirm } from "primevue/useconfirm";
import { router } from "@inertiajs/vue3";

export const responseError = (
    er: any,
    callback: Function = (option) => null
) => {
    if (typeof er != "object") return toast.error(er);
    Object.keys(er).forEach((el, i) => {
        if (i == 0) toast.error(er[el]);
    });
    callback(er);
};

export const responseSuccess = (e: any, callback = (option) => null) => {
    toast.success(e?.props?.success);
    callback(e);
};

export const preserveStateHandle = (page: any) => {
    return Object.keys(page.props.errors).length;
};

export const formHandle = async (
    forms,
    {
        routeName,
        paramRoute = null,
        method = "post",
        preserveScroll = true,
        preserveState = (page) => null,
        onSuccess = (e) => null,
        onError = (er) => null,
        onStart = () => null,
        onFinish = () => null,
        needConfirm = false,
        title = "Anda Yakin ?",
        text = "",
        prepare = (e) => null,
        item = null,
        loadName = null,
        elTarget = null,
    }
) => {
    const handle = () =>
        forms[method](route(routeName, paramRoute), {
            preserveScroll,
            preserveState: (page) => {
                return preserveState(page) ?? preserveStateHandle(page);
            },
            onSuccess: (e) => responseSuccess(e, (el) => onSuccess(el)),
            onError: (er) => responseError(er, (er) => onError(er)),
            onStart: () => {
                if (loadName && item) item[loadName] = true;
                onStart();
            },
            onFinish: () => {
                if (loadName && item) item[loadName] = false;
                onFinish();
            },
        });

    prepare(item);

    if (needConfirm) {
        useConfirm().require({
            target: elTarget,
            message: title,
            icon: "pi pi-exclamation-triangle",
            accept: () => handle(),
        });
        // if (confirm.isConfirmed) handle();
    } else handle();
};

export const editHandle = (
    form: Object,
    item: Object,
    except = [],
    callback = (option) => null
) => {
    Object.keys(form.data()).forEach((el) => {
        if (!except.includes(el)) form[el] = item[el];
    });
    callback(item);
};

export const removeHandle = async (
    item,
    {
        routeName = "",
        elTarget = null,
        loadName = "trash",
        removeRoute = (option) => null,
        onSuccess = (res) => null,
        onError = (er) => null,
        preserveState = (er) => false,
        id = "id",
    }
) => {
    // return formHandle(router, {
    //     routeName,
    //     paramRoute: { id: item[id] },
    //     elTarget,
    //     loadName,
    //     needConfirm: true,
    //     title: "Yakin ingin menghapus data ?",
    //     item,
    // });
    const handle = () => {
        router.delete(removeRoute(item) ?? route(routeName, { id: item[id] }), {
            preserveScroll: true,
            preserveState: preserveState,
            onStart: () => (item[loadName] = true),
            onFinish: () => (item[loadName] = false),
            onSuccess: (e) => responseSuccess(e, onSuccess),
            onError: (er) => responseError(er, onError),
        });
    };

    useConfirm().require({
        message: "Yakin ingin menghapus data ?",
        icon: "pi pi-exclamation-triangle",
        accept: () => handle(),
    });
};
