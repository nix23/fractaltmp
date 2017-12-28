// @todo -> Implement
import Prefixer from '~/wasm/BootWeb/Dom/Prefixer';
import Str from '~/wasm/Util/Str';

// const prefixedTransitionProps = [
//     "WebkitTransition", "MozTransition", "MsTransition",
//     "OTransition", "transition",
// ];
// const prefixedTransformProps = [
//     "WebkitTransform", "MozTransform", "OTransform",
//     "MsTransform", "transform",
// ];

class Css3 {
    transition(el, val) {
        el.style[Prefixer.get("transition", el)] = val;
    }

    transitionProp(el, prop) {
        var currentTransition = el.style[Prefixer.get("transition", el)];
        if(currentTransition.length == 0) {
            el.style[Prefixer.get("transition", el)] = prop;
            return;
        }

        var encodeCubicBezier = (transition) => {
            return transition.replace(
                /cubic-bezier\([^\)]+/g,
                (match) => { return match.replace(/,/g, ";"); }
            );
        };

        var decodeCubicBezier = (transition) => {
            return transition.replace(
                /cubic-bezier\([^\)]+/g,
                (match) => { return match.replace(/;/g, ","); }
            );
        }

        var newTransition = encodeCubicBezier(prop);
        currentTransition = encodeCubicBezier(currentTransition);
        var currentTransitionProps = currentTransition.split(",");

        for(var i = 0; i < currentTransitionProps.length; i++) {
            var currentTransitionProp = Str.trim(currentTransitionProps[i]);
            if(currentTransitionProp.length == 0)
                continue;

            var currentTransitionPropParts = currentTransitionProp.split(" ");
            var currentTransitionPropName = currentTransitionPropParts[0];

            if(newTransition.search(currentTransitionPropName) === -1) {
                newTransition += ", " + currentTransitionProp;
            }
        }

        el.style[Prefixer.get("transition", el)] = Str.trim(
            decodeCubicBezier(newTransition)
        );
    }

    transform(el, val) {
        el.style[Prefixer.get("transform", el)] = val;
    }

    transformProp(el, prop, val) {
        var currentTransform = el.style[Prefixer.get('transform', el)];
        if(currentTransform.length == 0) {
            el.style[Prefixer.get('transform', el)] = prop + "(" + val + ")";
            return;
        }

        var newTransform = "";
        var currentTransformProps = currentTransform.split(/\)/);
        var hasCurrentTransformProperty = false;
        for(var i = 0; i < currentTransformProps.length; i++) {
            var currentTransformProp = Str.trim(currentTransformProps[i]);
            if(Str.trim(currentTransformProp).length == 0)
                continue;

            if(currentTransformProp.search(prop) !== -1) {
                newTransform += " " + prop + "(" + val + ")";
                hasCurrentTransformProperty = true;
            }
            else {
                newTransform += " " + currentTransformProp + ")";
            }
        }

        if(!hasCurrentTransformProperty)
            newTransform += " " + prop + "(" + val + ")";

        el.style[Prefixer.get('transform', el)] = Str.trim(newTransform);
    }

    opacity(el, val) {
        const props = ["-webkit-opacity", "-moz-opacity", "opacity"];
        for(var i = 0; i < props.length; i++)
            el.style[props[i]] = val;
    }

    perspective(el, val) {
        const props = ["WebkitPerspective", "perspective", "MozPerspective"];
        for(var i = 0; i < props.length; i++)
            el.style[props[i]] = val;
    }

    transformStyle(el, val) {
        const props = ["transformStyle", "WebkitTransformStyle", "MozTransformStyle"];
        for(var i = 0; i < props.length; i++)
            el.style[props[i]] = val;
    }

    backfaceVisibility(el, val) {
        const props = [
            "WebkitBackfaceVisibility", "MozBackfaceVisibility", "backfaceVisibility",
        ];
        for(var i = 0; i < props.length; i++)
            el.style[props[i]] = val;
    }

    transformOrigin(el, val) {
        const props = [
            "webkitTransformOrigin", "mozTransformOrigin", "oTransformOrigin",
            "msTransformOrigin", "transformOrigin",
        ];
        for(var i = 0; i < props.length; i++) {
            if(typeof el.style[props[i]] != "undefined")
                el.style[props[i]] = val;
        }
    }
};

export default new Css3();