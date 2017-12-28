import breakpointsReducer from '~/wasm/BootWeb/Breakpoints/Store/reducer';
import dataFormSearchDemoReducer from '~/wasm/BootWeb/_debug/Form/Search/Store/reducer';

export default {
    app: breakpointsReducer,
    formSearchDemo: dataFormSearchDemoReducer,
};