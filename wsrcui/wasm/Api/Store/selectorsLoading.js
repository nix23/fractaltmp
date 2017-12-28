import Util from '~/wasm/Util/Util';

export const isLoading = (state) => {
    let hasLoadingMore = Util.hasProp(state, "isLoadingMore");
    let hasRefreshing = Util.hasProp(state, "isRefreshing");

    if(hasLoadingMore && hasRefreshing)
        return state.isLoading || state.isLoadingMore || state.isRefreshing;
    else if(hasLoadingMore)
        return state.isLoading || state.isLoadingMore;
    else
        return state.isLoading;
};