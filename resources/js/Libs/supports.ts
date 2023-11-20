export const asset = (path: string) => {
    // default to MIX_ASSET_URL
    let prefix = import.meta.env.APP_URL;
    if (!prefix) {
        // fallback to determining ASSET_URL from meta tag
        prefix = document.head.querySelector('meta[name="asset-url"]').content;
    }
    if (typeof path != "string") return;
    return prefix.replace(/\/+$/, "") + "/" + path?.replace(/^\/+/, "") ?? "";
};
