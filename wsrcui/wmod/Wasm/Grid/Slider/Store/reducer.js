import Reducer from '~/wasm/Store/Reducer';

export default withReducer = (actions, initialState) => {
    let reducers = {
        [actions.LOAD] = (state) => ({
            ...state,
            isLoading: true,
            items: [...state.items],
        }),
        [actions.LOAD_OK] = (state, action) => ({
            ...state,
            isLoading: false,
            items: action.items,
        }),
        [actions.SHOW_ITEM] = (state, action) => ({
            ...state,
            items: [...state.items],
            showItemId: action.id,
        }),
    };

    return Reducer.reduce(actions, reducers, initialState);
};