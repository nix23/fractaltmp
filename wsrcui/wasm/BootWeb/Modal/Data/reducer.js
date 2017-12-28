import { actions } from './actions';

const initialState = {
    modals: [],
};

export default (state = initialState, action) => {
    switch(action.type) {
        case actions.PUSH_MODAL_COMPONENT:
            return {
                ...state,
                modals: [
                    ...state.modals,
                    {
                        id: action.modalId,
                        props: action.props,
                    }
                ],
            };
        case actions.MERGE_MODAL_COMPONENT_PROPS:
            // @todo -> Replace with Util
            let nextModals = [];
            for(let i = 0; i < state.modals.length; i++) {
                if(state.modals[i].id != action.modalId)
                    nextModals.push(state.modals[i]);
                else
                    nextModals.push({
                        ...state.modals[i],
                        props: {
                            ...state.modals[i].props,
                            ...action.nextProps,
                        },
                    });
            }

            return {
                ...state,
                modals: nextModals,
            };
        case actions.POP_MODAL_COMPONENT:
            // @todo -> Replace with Util
            nextModals = [];
            for(let i = 0; i < state.modals.length - 1; i++)
                nextModals.push(state.modals[i]);

            return {
                ...state,
                modals: nextModals,
            };
        case actions.RM_MODAL_COMPONENT:
            // @todo -> Replace with Util
            nextModals = [];
            for(let i = 0; i < state.modals.length; i++) {
                if(state.modals[i].id != action.modalId)
                    nextModals.push(state.modals[i]);
            }

            return {
                ...state,
                modals: nextModals,
            };
        case actions.RM_ALL_MODAL_COMPONENTS:
            return {
                ...state,
                modals: [],
            };
        default:
            return state;
    }
};