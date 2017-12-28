import moment from 'moment';
import Arr from '~/wasm/Util/Arr';
import Util from '~/wasm/Util/Util';

class Items {
    includes(items, item) {
        if(Array.isArray(items)) {
            let cItem = items.filter((cItem) => item.id == cItem.id);
            return (cItem.length > 0);
        }

        return Util.hasProp(items, item.id);
    }

    getIndex(items, item) {
        for(let i = 0; i < items.length; i++) {
            if(items[i].id == item.id)
                return i;
        }

        return -1;
    }

    prepend(curr, next) {
        return this.merge(curr, next);
    }

    append(curr, next) {
        return this.merge(curr, next, true);
    }

    merge(curr, next, appendNew = false) {
        next = Arr.forceArray(next);
        
        let { merge, add } = next.reduce(
            (res, item) => {
                let arr = (this.includes(curr, item)) ? "merge" : "add";
                res[arr].push(item);

                return res;
            },
            {
                merge: [],
                add: [],
            },
        );

        let items = {};
        if(Array.isArray(curr))
            items = (!appendNew) ? [...add, ...curr] : [...curr, ...add];
        else {
            for(let id in curr)
                items[id] = {...curr[id]};
            add.map((item) => { items[item.id] = item; });
        }

        merge.map((item) => {
            if(!Array.isArray(curr))
                items[item.id] = {...items[item.id], ...item};
            else {
                let index = this.getIndex(items, item);
                items[index] = {...items[index], ...item};
            }
        });

        return items;
    }

    // @todo -> Support dictionaries?
    mergeOptimistic(curr, next, ids) {
        for(var i = 0; i < ids.length; i++) {
            next[i].id = ids[i];

            curr = this.merge(curr, [next[i]]);
            curr.map((item) => {
                if(item.id == ids[i])
                    item.id = next[i].id;
            });
        }

        return curr;
    }

    // @todo -> Support dictionaries?
    mergeCopiesIntoFirst(curr) {
        let items = [];
        let copies = [];

        curr.map((item) => {
            let isCopy = items.filter((i) => {
                return i.id == item.id;
            }).length > 0;

            let arr = (isCopy) ? copies : items;
            arr.push(item);
        });

        return (copies.length == 0) ? curr : this.merge(items, copies);
    }

    sortByDate(items, field = "createdAt", desc = true) {
        return items.sort(function(a, b) {
            let aval = moment(a[field]).unix();
            let bval = moment(b[field]).unix();

            return (!desc) ? aval - bval : bval - aval;
        });
    }

    sortByDateAsc(items, field = "createdAt") {
        return this.sortByDate(items, field, false);
    }

    // @todo -> Mv to ItemsCollection.initEntryById/objEntryById
    //       -> ItemsById?, ItemsDataById
    initCollectionByIdEntry(obj, entryId) {
        if(Util.hasProp(obj, entryId) &&
           Util.hasProp(obj[entryId], "items"))
            return obj;

        return {
            ...obj,
            [entryId]: {
                items: [],
                itemsCount: 0,
            },
        };
    }

    mergeCollectionByIdEntries(obj, entryId, items, itemsCount) {
        return {
            ...obj,
            [entryId]: {
                items: this.merge(
                    obj[entryId].items,
                    items,
                ),
                itemsCount: itemsCount,
            },
        };
    }
};

export default new Items();
