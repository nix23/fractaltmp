import Str from '~/wasm/Util/Str';

export const breakpoints = ["xs", "sm", "md", "lg", "xl"];

const getBreakpoint = () => (dispatch, getState) => {
    return Promise.resolve().then(() => getState().app.breakpoints.width);
};

const isMin = (v, minV) => breakpoints.indexOf(v) >= breakpoints.indexOf(minV);
const isMax = (v, maxV) => breakpoints.indexOf(v) <= breakpoints.indexOf(maxV);
const isBetween = (v, minV, maxV) => isMin(v, minV) && isMax(v, maxV);

const withIsMin = (minValue) => {
    return () => (dispatch, getState) => {
        return getBreakpoint().then((bpValue) => isMin(bpValue, minValue));
    };
};

const withIsMax = (maxValue) => {
    return () => (dispatch, getState) => {
        return getBreakpoint().then((bpValue) => isMax(bpValue, maxValue));
    };
};

const withIsOnly = (onlyValue) => {
    return () => (dispatch, getState) => {
        return getBreakpoint().then((bpValue) => bpValue == onlyValue);
    };
};

const withIsBetween = (minValue, maxValue) => {
    return () => (dispatch, getState) => {
        return getBreakpoint().then((bpValue) => isBetween(bpValue, minValue, maxValue));
    };
};

let exports = {};
breakpoints
    .map((value, i) => {
        const ucValue = Str.ucfirst(value);
        exports["isMin" + ucValue] = withIsMin(value);
        exports["isMax" + ucValue] = withIsMax(value);
        exports["isOnly" + ucValue] = withIsOnly(value);

        breakpoints.slice(breakpoints.indexOf(value) + 1).map((endValue) => {
            let name = "isBetween" + ucValue + "And" + Str.ucfirst(endValue);
            let fn = withIsBetween(value, endValue);

            exports[name] = fn;
        });
    });

export default exports;