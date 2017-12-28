class Util {
    guid() {
        var d = new Date().getTime();
        if(typeof performance !== 'undefined' && typeof performance.now === 'function')
            d += performance.now(); //use high-precision timer if available
        
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    void() {
        return;
    }

    args(returnValue = {}) {
        return () => {
            return returnValue;
        };
    }

    int(maybeInt) {
        return parseInt(maybeInt, 10);
    }

    toFixed(value, precision) {
        return parseFloat(+(Math.round(+(value.toString() + 'e' + precision)).toString() + 'e' + -precision));
    }

    areRoundedOrFlooredValuesEqual(a, b) {
        return (Math.round(a) == Math.round(b) || Math.floor(a) == Math.floor(b));
    }

    areRoundedOrCeiledValuesEqual(a, b) {
        return (Math.round(a) == Math.round(b) || Math.ceil(a) == Math.ceil(b));
    }

    isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    isObj(maybeObj) {
        return typeof maybeObj === "object" && maybeObj !== null;
    }

    isFunc(maybeFunc) {
        return typeof maybeFunc === "function";
    }

    isBool(maybeBool) {
        return typeof maybeBool === "boolean";
    }

    isUndef(maybeUndef) {
        return typeof maybeUndef === "undefined";
    }

    hasProp(obj, prop) {
        return typeof obj[prop] !== "undefined";
    }

    ensureDefined(obj, prop, defVal = null) {
        if(typeof(obj[prop]) === "undefined")
            obj[prop] = defVal;
    }

    getIfDefined(obj, prop, undefVal = "", getProp = (obj) => obj) {
        if(typeof(obj[prop]) === "undefined")
            return undefVal;

        return getProp(obj[prop]);
    }
};

export default new Util();