export const actions = {
    LOAD_SEARCH: "wasm_boot/DEBUG_LOAD_SEARCH",
    LOAD_SEARCH_OK: "wasm_boot/DEBUG_LOAD_SEARCH_OK",
};

const loadSearch = () => {
    return {
        type: actions.LOAD_SEARCH,
    };
};

const loadSearchOk = (items) => {
    return {
        type: actions.LOAD_SEARCH_OK,
        items: items,
    };
};

// Page -> page || 0
// data.itemsCount > 0 ? onFound : onNotFound
export const fetchSearch = (
    searchValue, 
    isLastSearch, 
    loading
) => (dispatch, getState) => {
    let state = getState();

    loading().on();
    return Promise.resolve()
        .then(() => {
            if(!isLastSearch(getState(), searchValue))
                return;
            loading().off();

            let items = [
                {id: 1, name: 'ItemA'},
                {id: 2, name: 'ItemB'},
            ];
            dispatch(loadSearchOk(items));
        });
};