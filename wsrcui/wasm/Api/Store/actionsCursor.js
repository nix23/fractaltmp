import fetchTypes from './fetchTypes';
import Util from '~/wasm/Util/Util';

export const mergeCursor = (
    fetchType,
    cursor = null,
    params = {}
) => {
    if(!cursor)
        return params;

    if(fetchType == fetchTypes.FETCH_MORE)
        params.cursor = cursor.prev;

    if(fetchType == fetchTypes.REFRESH)
        params.cursor = cursor.next;

    // If fetchType == fetchTypes.FETCH cursor will not be added
    return params;
};

export const getCollectionByIdCursor = (collection, entryId) => {
    if(Util.hasProp(collection, entryId) &&
       Util.hasProp(collection[entryId], "cursor"))
        return collection[entryId]["cursor"];

    return null;
};