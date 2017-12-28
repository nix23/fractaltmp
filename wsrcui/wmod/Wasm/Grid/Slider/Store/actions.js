import Api from '~/wasm/Api/Api';

import fetchTypes from '~/wasm/Api/Store/fetchTypes';
import { isLoading } from '~/wasm/Api/Store/selectorsLoading';

export default withActions = (actions, url) => {
    let fns = {};

    const load = () => ({
        type: actions.LOAD,
    });
    const loadOk = (items) => ({
        type: actions.LOAD_OK,
        items: items,
    });

    fns.fetch = () => (dispatch, getState) {
        dispatch(load());
        return Api.get(url).then(json => {
            dispatch(loadOk(json.items));

            if(!hasNoActiveSlide(getState()) && json.items.length > 0)
                dispatch(showItem(json.items[0].id));
        });
    };

    const showItem = (id) => ({
        type: actions.SHOW_ITEM,
        id: id,
    });

    return fns;
};