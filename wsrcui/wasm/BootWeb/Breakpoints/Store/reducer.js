import { actions } from './actions';

const initialState = {
    breakpoints: {
        width: "",
        orientation: "landscape",
    },
};

export default (state = initialState, action) => {
    switch(action.type) {
        case actions.SET_BREAKPOINT:
            return {
                ...state,
                breakpoints: {
                    ...state.breakpoints,
                    width: action.width,
                },
            };
        case actions.SET_ORIENTATION_BREAKPOINT:
            return {
                ...state,
                breakpoints: {
                    ...state.breakpoints,
                    orientation: action.orientation,
                },
            };
        default:
            return state;
    }
};
