class Url {
    constructor() {
        this.nextUrl = "";
    }

    setNextUrl(url) {
        this.nextUrl = url;
    }

    getNextUrl() {
        let url = this.nextUrl;
        this.nextUrl = "";

        if(url.length == 0)
            throw new Error("Api.nextUrl is empty.(Did you forgot to set it?)");

        return url;
    }

    encodeQueryString(a) {
        var s = [];
        var rbracket = /\[\]$/;

        var isArray = (obj) => {
            return Object.prototype.toString.call(obj) === '[object Array]';
        }; 
        var add = (k, v) => {
            v = typeof v === 'function' ? v() : v === null ? '' : v === undefined ? '' : v;
            s[s.length] = encodeURIComponent(k) + '=' + encodeURIComponent(v);
        };

        var buildParams = (prefix, obj) => {
            var i, len, key;

            if(prefix) {
                if(isArray(obj)) {
                    for(i = 0, len = obj.length; i < len; i++) {
                        if(rbracket.test(prefix))
                            add(prefix, obj[i]);
                        else
                            buildParams(prefix + '[' + (typeof obj[i] === 'object' ? i : '') + ']', obj[i]);
                    }
                } 
                else if(obj && String(obj) === '[object Object]') {
                    for(key in obj)
                        buildParams(prefix + '[' + key + ']', obj[key]);
                } 
                else
                    add(prefix, obj);
            } 
            else if(isArray(obj)) {
                for(i = 0, len = obj.length; i < len; i++)
                    add(obj[i].name, obj[i].value);
            } 
            else {
                for(key in obj)
                    buildParams(key, obj[key]);
            }

            return s;
        };

        return buildParams('', a).join('&').replace(/%20/g, '+');
    }
}

module.exports = new Url();