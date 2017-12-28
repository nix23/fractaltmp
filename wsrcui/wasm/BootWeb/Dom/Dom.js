// @todo -> Implement
import Prefixer from '~/wasm/BootWeb/Dom/Prefixer';

let hasOwnProp = () => {};
let hasBrowserTransitions = false;

class Dom {
    initialize() {
        this.createHasOwnPropFn();
        this.findHasBrowserTransitions();
    }

    // ie11, ff30(Probably some others too) doesn't support
    // Object.prototype.hasOwnProperty.call per DOM Objects
    createHasOwnPropFn() {
        var tester = document.createElement("div");
        var root = document.body || document.documentElement;
        root.appendChild(tester);

        if(Object.prototype.hasOwnProperty.call(tester, "innerHTML")) {
            hasOwnProp = (el, prop) => {
                return Object.prototype.hasOwnProperty.call(el, prop);
            };
        }
        else {
            hasOwnProp = (el, prop) => {
                for(var elProp in el) {
                    if(elProp == prop)
                        return true;
                }

                return false;
            };
        }

        root.removeChild(tester);
    }

    findHasBrowserTransitions() {
        var tester = document.createElement("div");
        var names = [
            'WebkitTransition', 'MozTransition',
            'OTransition', 'msTransition', 
            'MsTransition', 'transition'
        ];

        for(var i = 0; i < names.length; i++) {
            if(tester.style[names[i]] !== undefined)
                hasBrowserTransitions = true;
        }
    }

    isDomNode(maybeDom) {
        if(typeof maybeDom != "undefined"
            && typeof maybeDom.tagName != "undefined"
            && typeof maybeDom.nodeName != "undefined"
            && typeof maybeDom.ownerDocument != "undefined"
            && typeof maybeDom.removeAttribute != "undefined")
            return true;
        else
            return false;
    }

    isChildOf(maybeChild, container) {
        if(maybeChild == container)
            return false;

        var nextParent = maybeChild.parentNode;
        while(nextParent != undefined) {
            if(nextParent == container)
                return true;

            if(nextParent == document.body)
                break;

            nextParent = nextParent.parentNode;
        }

        return false;
    }

    hasBrowserTransitions() {
        return hasBrowserTransitions;
    }

    hasOwnProp(item, prop) {
        return hasOwnProp(item, prop);
    }
};

export default new Dom();