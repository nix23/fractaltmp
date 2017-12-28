class Attr {
    get(el, attr) {
       return el.getAttribute(attr);
    }

    set(el, attr, val) {
        if(Array.isArray(attr)) {
            for(let i = 0; i < attr.length; i++)
                el.setAttribute(attr[i][0], attr[i][1]);
            return;
        }

        el.setAttribute(attr, val);
    }

    has(el, attr) {
        if((el.getAttribute(attr) === null) || 
           (el.getAttribute(attr) === ''))
            return false;

        return true;
    }

    rm(el, attr) {
        el.removeAttribute(attr);
    }

    rmIfHas(el, attr) {
        if(Array.isArray(attr)) {
            for(var prop in attr) {
                if(this.has(el, attr[prop]))
                    this.rm(el, attr[prop]);
            }
            return;
        }

        if(this.has(el, attr))
            this.rm(el, attr);
    }
};

export default new Attr();