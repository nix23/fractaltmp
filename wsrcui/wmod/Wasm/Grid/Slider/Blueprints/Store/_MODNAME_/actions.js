import withActions from '~/wmod/Wasm/Grid/Slider/Store/actions';

const url = "<%ROUTE%>";

export const actions = {
    LOAD: "<%PKGMODNAME%>/LOAD",
    LOAD_OK: "<%PKGMODNAME%>/LOAD_OK",
    SHOW_ITEM: "<%PKGMODNAME%>/SHOW_ITEM",
};
export default withActions(actions, url);