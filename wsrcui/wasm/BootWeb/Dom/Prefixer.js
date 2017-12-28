// @todo -> Implement
import Str from '~/wasm/Util/Str';
const prefixes = ['Moz', 'Webkit', 'ms', 'Ms', 'Khtml', 'O'];

class Prefixer {
    getPrefixedProp(prop, el = document.documentElement, getPrefixedProp) {
        let style = el.style;

        if(typeof style[prop] === "string")
            return prop;

        let origProp = prop;
        prop = Str.ucfirst(prop);

        for(var i = 0; i < prefixes.length; i++) {
            var prefixedProp = prefixes[i] + prop;
            if(typeof style[prefixedProp] === "string")
                return getPrefixedProp(prefixedProp, origProp, i);
        }
    }

    get(prop, el) {
        return this.getPrefixedProp(
            prop, 
            el, 
            (prop) => prop
        );
    }

    getForCss(prop, el) {
        return this.getPrefixedProp(
            prop,
            el,
            (prop, origProp, i) => {
                return "-" + prefixes[i].toLowerCase() + "-" + origProp;
            }
        )
    }
};

export default new Prefixer();