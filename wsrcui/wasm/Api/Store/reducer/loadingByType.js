import fetchTypes from '../fetchTypes';

const reduceLoadingByFetchType = (state, action, value = true) => {
    switch(action.fetchType) {
        case fetchTypes.FETCH: 
            return {
                ...state,
                isLoading: value,
            };
        case fetchTypes.FETCH_MORE:
            return {
                ...state,
                isLoadingMore: value,
            };
        case fetchTypes.REFRESH:
            return {
                ...state,
                isRefreshing: value,
            };
    }
};

export const on = (state, action) => {
    return reduceLoadingByFetchType(state, action);
};

export const off = (state, action) => {
    return reduceLoadingByFetchType(state, action, false);
};