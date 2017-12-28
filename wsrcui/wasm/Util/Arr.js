import Util from '~/wasm/Util/Util';

class Arr {
    last(arr) {
        return arr[arr.length - 1];
    }

    containsAll(needles, haystack) {
        return needles.every((item) => haystack.indexOf(item) >= 0);
    }

    maybeObjKeysToArray(maybeObj) {
        if(!Array.isArray(maybeObj)) {
            return Object.keys(maybeObj).reduce((res, key) => {
                res.push(key);
                return res;
            }, []);
        }

        return maybeObj;
    }

    reduceToObj(objsArray) {
        return objsArray.reduce((res, item) => {
            for(var prop in item)
                res[prop] = item[prop];

            return res;
        }, {});
    }

    forceArray(maybeArray) {
        return Array.isArray(maybeArray) ? maybeArray : [maybeArray];
    }

    toggle(arr, item) {
        if(arr.includes(item))
            return this.rm(arr, item);
        else
            return [...arr, item];
    }

    wasValToggled(val, arr, prevArr) {
        return (!prevArr.includes(val) && arr.includes(val)) ||
               (prevArr.includes(val) && !arr.includes(val));
    }

    addUnique(arr, items) {
        items = this.forceArray(items);
        let newItems = items.reduce((res, item) => {
            if(!arr.includes(item) && !res.includes(item))
                res.push(item);

            return res;
        }, []);

        return [...arr, ...newItems];
    }

    rmUnique(arr, items) {
        items = this.forceArray(items);
        let newArr = arr.reduce((res, item) => {
            if(!items.includes(item))
                res.push(item);

            return res;
        }, []);

        return newArr;
    }

    rm(arr, item) {
        return [
            ...arr.slice(0, arr.indexOf(item)),
            ...arr.slice(arr.indexOf(item) + 1),
        ];
    }

    rmAll(arr, item) {
        while(arr.includes(item))
            arr = this.rm(arr, item);

        return arr;
    }

    shift(arr) {
        return [
            ...arr.slice(1),
        ];
    }

    toPairs(arr) {
        return this.toTuples(arr);
    }

    toTuples(arr, tupleSize = 2) {
        if(arr.length == 0)
            return [];

        let tuples = [];
        let nextTuple = [];
        let i = 0;

        do {
            nextTuple.push(arr[i]);

            if(nextTuple.length == tupleSize) {
                tuples.push(nextTuple);
                nextTuple = [];
            }

            i++;
        } while(i < arr.length);

        if(nextTuple.length > 0)
            tuples.push(nextTuple);

        return tuples;
    }

    reduceByComparator(items, comparator, pusher) {
        return items.reduce((res, item) => {
            if(comparator(item))
                res.push(pusher(item));

            return res;
        }, []);
    }

    closestToEndByCond(items, getResult, comparator) {
        if(!Util.isFunc(getResult))
            getResult = (item) => (item != null) ? item.id : false;
        if(!Util.isFunc(comparator))
            comparator = (item) => (parseInt(item.id, 10) >= 0);

        for(let i = items.length - 1; i >= 0; i--) {
            if(comparator(items[i]))
                return getResult(items[i]);
        }

        return getResult(null);
    }

    closestToStartByCond(items, getResult, comparator) {
        if(!Util.isFunc(getResult))
            getResult = (item) => (item != null) ? item.id : false;
        if(!Util.isFunc(comparator))
            comparator = (item) => (parseInt(item.id, 10) >= 0);

        for(let i = 0; i < items.length; i++) {
            if(comparator(items[i]))
                return getResult(items[i]);
        }

        return getResult(null);
    }
};

export default new Arr();