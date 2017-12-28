import Dom from '~/wasm/BootWeb/Dom/Dom';

class Validator {
    ensureHasComputedProp(elComputedCss, prop) {
        if(!(prop in elComputedCss))
            throw new Error("No prop '" + prop + "' in measureSizes computedCss.");
    }

    ensureHasParentNode(el) {
        if(el.parentNode == null || !Dom.hasOwnProp(el.parentNode, "innerHTML"))
            throw new Error("No parentNode for Dom node: ", el);
    }
};


export default new Validator();