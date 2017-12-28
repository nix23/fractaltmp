let getComputedCss = null;

class SizesComputedCss {
    initialize() {
        this.initializeGetComputedCss();
    }

    initializeGetComputedCss() {
        if(window.getComputedStyle) {
            getComputedCss = (el) => {
                return window.getComputedStyle(el, null);
            };
        }
        else {
            getComputedCss = (el) => {
                return el.currentStyle;
            };
        }
    }

    getComputedCss(el) {
        return getComputedCss(el);
    }

    flushComputedCss(el) {
        return getComputedCss(el).opacity;
    }

    getComputedProps(propNames, elComputedCss, el) {
        var computedProps = {};

        for(var i = 0; i < propNames.length; i++) {
            var propName = propNames[i];
            var propVal = elComputedCss[propName];

            if(this.isCascadedCssVal(propVal))
                propVal = this.cascadedToComputed(el, propVal, elComputedCss);
            propVal = parseFloat(propVal);
            propVal = isNaN(propVal) ? 0 : propVal;

            computedProps[propName] = propVal;
        }

        return computedProps;
    }

    normalizeComputedCss(postfixedSizeValue) {
        var sizeValue = parseFloat(postfixedSizeValue);
        var canBeParsedAsNumber = postfixedSizeValue.indexOf("%") === -1 && !isNaN(sizeValue);
        
        return (canBeParsedAsNumber) ? sizeValue : false;
    }

    getUncomputedCss(el) {
        var parentElClone = el.parentNode.cloneNode();
        var elClone = el.cloneNode();

        parentElClone.appendChild(elClone);
        parentElClone.style.display = "none";
        
        var parentElParent = (el.parentNode.nodeName == "HTML") 
            ? el.parentNode 
            : el.parentNode.parentNode;
        parentElParent.appendChild(parentElClone);
        
        var uncomputedCssSource = this.getComputedCss(elClone);
        var uncomputedCss = {};

        var props = ["paddingLeft", "paddingRight", "paddingTop", "paddingBottom",
                     "marginLeft", "marginRight", "marginTop", "marginBottom",
                     "width", "height"];
        for(var i = 0; i < props.length; i++)
            uncomputedCss[props[i]] = uncomputedCssSource[props[i]];
        
        parentElParent.removeChild(parentElClone);
        
        return uncomputedCss;
    }

    // Based on http://erik.eae.net/archives/2007/07/27/18.54.15/#comment-102291
    // and http://javascript.info/tutorial/styles-and-classes-getcomputedstyle.
    // IE currentStyle returns cascaded style instead of computed style,
    // so if we have unit other than px, we should recalculate it in px.
    isCascadedCssVal(cssValue) {
        return (window.getComputedStyle || cssValue.indexOf("px") !== -1) ? false : true;
    }

    cascadedToComputed(item, cssValue, itemComputedCss) {
        // Check value auto, medium, etc...
        var atLeastOneDigitRegex = new RegExp("(?=.*\\d)");
        if(!atLeastOneDigitRegex.test(cssValue))
            return cssValue;

        var inlineStyle = item.style;
        var runtimeStyle = item.runtimeStyle;

        var inlineStyleLeft = inlineStyle.left;
        var runtimeStyleLeft = runtimeStyle && runtimeStyle.left;

        if(runtimeStyleLeft)
            runtimeStyle.left = itemComputedCss.left;

        inlineStyle.left = cssValue;
        cssValue = inlineStyle.pixelLeft;

        inlineStyle.left = inlineStyleLeft;
        if(runtimeStyleLeft)
            runtimeStyle.left = runtimeStyleLeft;

        return cssValue;
    }
};

export default new SizesComputedCss();