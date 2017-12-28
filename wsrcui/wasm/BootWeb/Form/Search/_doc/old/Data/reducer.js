import { actions } from './actions';

const initialState = {
    byFormId: {},
};

export const INITIAL_INTERACTION_ID = 0;

export default (state = initialState, action) => {
    switch(action.type) {
        case actions.CREATE_FORM_SEARCH_DATA_BY_ID:
            return {
                ...state,
                byFormId: {
                    ...state.byFormId,
                    [action.formId]: {
                        focusedItemIndex: null,
                        wasFocusedWithKeyUp: false,
                        searchValue: "",
                        // onInputKeyUp || onInputOverlayClick
                        // onFocus || onPaste || onCut || onBlur
                        inputInteractionId: INITIAL_INTERACTION_ID,
                        isLoading: false,
                    },
                },
            };
        case actions.RM_FORM_SEARCH_DATA_BY_ID:
            // @todo -> Replace with Util
            let nextForms = {};
            for(let key in state.byFormId) {
                if(key != action.formId)
                    nextForms[key] = state.byFormId;
            }

            return {
                ...state,
                byFormId: nextForms,
            };
        case actions.SET_FORM_SEARCH_VALUE:
            nextForms = {};
            for(let key in state.byFormId) {
                if(key != action.formId)
                    nextForms[key] = state.byFormId;
                else
                    nextForms[key] = {
                        ...state.byFormId[key],
                        searchValue: action.nextValue,
                    };
            }

            return {
                ...state,
                byFormId: nextForms,
            };
        case actions.LOADING_ON:
            nextForms = {};
            for(let key in state.byFormId) {
                if(key != action.formId)
                    nextForms[key] = state.byFormId;
                else
                    nextForms[key] = {
                        ...state.byFormId[key],
                        isLoading: true,
                    };
            }

            return {
                ...state,
                byFormId: nextForms,
            };
        case actions.LOADING_OFF:
            nextForms = {};
            for(let key in state.byFormId) {
                if(key != action.formId)
                    nextForms[key] = state.byFormId;
                else
                    nextForms[key] = {
                        ...state.byFormId[key],
                        isLoading: false,
                    };
            }

            return {
                ...state,
                byFormId: nextForms,
            };
        case actions.TRIGGER_INPUT_INTERACTION:
            nextForms = {};
            for(let key in state.byFormId) {
                if(key != action.formId)
                    nextForms[key] = state.byFormId;
                else {
                    let nextId = ++state.byFormId[key].inputInteractionId;
                    
                    if(nextId == 100000)
                        nextId = 0;
                    
                    nextForms[key] = {
                        ...state.byFormId[key],
                        inputInteractionId: nextId,
                    };
                }
            }

            return {
                ...state,
                byFormId: nextForms,
            };
        default:
            return state;
    }
};