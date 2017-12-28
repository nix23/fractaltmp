import { actions } from './actions';
import withReducer from '~/wmod/Wasm/Grid/Slider/Store/reducer';

const initialState = {
    showItemId: -1,
    isLoading: false,
    items: [],
};
export default withReducer(actions, initialState);