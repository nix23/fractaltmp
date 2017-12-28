import fetchTypes from '../fetchTypes';

import Items from '~/wasm/Store/Items';
import Util from '~/wasm/Util/Util';

const mergeNextCursor = (state, action) => {
    if(!Util.hasProp(state, "cursor"))
        return action.cursor;

    return {
        ...state.cursor,
        ...action.cursor,
    };
};

export const items = (state, action) => {
    let nextState;

    switch(action.fetchType) {
        case fetchTypes.FETCH:
             nextState = {
                ...state,
                items: action.items,
                cursor: (Util.hasProp(action, "cursor"))
                    ? action.cursor 
                    : null,
            };
            break;
        case fetchTypes.FETCH_MORE:
            // @todo -> Check no new items?(in action.items)
            nextState = {
                ...state,
                items: Items.append(state.items, action.items),
                cursor: (Util.hasProp(action, "cursor")) 
                    ? mergeNextCursor(state, action) 
                    : null,
            };
            break;
        case fetchTypes.REFRESH:
            nextState = {
                ...state,
                items: Items.prepend(state.items, action.items),
                cursor: (Util.hasProp(action, "cursor")) 
                    ? mergeNextCursor(state, action) 
                    : null,
            };
            break;
    }

    if(Util.hasProp(action, "itemsCount"))
        nextState.itemsCount = action.itemsCount;

    return nextState;
};

export const collectionById = (
    collectionState, 
    entryId, 
    action
) => {
    return {
        ...collectionState,
        [entryId]: items(
            collectionState[entryId],
            action
        ),
    };
};