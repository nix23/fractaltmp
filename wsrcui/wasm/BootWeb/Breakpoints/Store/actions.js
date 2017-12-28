// const routes = {
//     devices: 'accounts/devices',
// };

export const actions = {
    SET_BREAKPOINT: "wasm_boot/SET_BREAKPOINT",
    SET_ORIENTATION_BREAKPOINT: "wasm_boot/SET_ORIENTATION_BREAKPOINT",
};

export const setBreakpoint = (width = "") => {
    return {
        type: actions.SET_BREAKPOINT,
        width: width,
    };
};

export const setOrientationBreakpoint = (orientation = "") => {
    return {
        type: actions.SET_ORIENTATION_BREAKPOINT,
        orientation: orientation,
    };
};