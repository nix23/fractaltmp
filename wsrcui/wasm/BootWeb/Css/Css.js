// @todo -> Implement
import Str from '~/wasm/Util/Str';
import Util from '~/wasm/Util/Util';

class Css {
    set(el, params = {}) {
        for(var prop in params)
            el.style[prop] = params[prop];
    }

    set4(el, prop, val) {
        var sides = ["Left", "Right", "Top", "Bottom"];
        for(var i = 0; i < sides.length; i++)
            el.style[prop + sides[i]] = (Util.isObj(val)) 
                ? val[prop + sides[i]] 
                : val;
    }

    hasClass(el, classToFind) {
        var classes = el.getAttribute("class");
        if(classes == null || classes.length == 0)
            return false;

        var classes = classes.split(" ");

        for(var i = 0; i < classes.length; i++) {
            classes[i] = Str.trim(classes[i]);
            if(classes[i] == classToFind)
                return true;
        }

        return false;
    }

    addClass(el, classToAdd) {
        var currentClass = el.getAttribute("class");
        if(currentClass == null || currentClass.length == 0)
            var newClass = classToAdd;
        else
            var newClass = currentClass + " " + classToAdd;

        el.setAttribute("class", newClass);
    }

    rmClass(el, classToRm) {
        var classes = el.getAttribute("class").split(" ");
        var cleanedClass = "";

        for(var i = 0; i < classes.length; i++) {
            if(Str.trim(classes[i]) != classToRm)
                cleanedClass += classes[i] + " ";
        }
        cleanedClass = cleanedClass.substring(0, cleanedClass.length - 1);

        el.setAttribute("class", cleanedClass);
    }
};

export default new Css();