import { actions } from './actions';

const initialState = {
    items: [],
};

export default (state = initialState, action) => {
    switch(action.type) {
        case actions.LOAD_SEARCH:
            return {
                ...state,
            };
        case actions.LOAD_SEARCH_OK:
            return {
                ...state,
                items: action.items,
            };
        default:
            return state;
    }
};
