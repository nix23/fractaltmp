import SizesComputedCss from './ComputedCss';
import BorderBoxType from './BorderBoxType';
import PtValsCalcType from './PtValsCalcType';
import CloneComputedCss from './CloneComputedCss';
import SizesValidator from './Validator';

let lastRawWidth = null;
let lastRawHeight = null;
let lastBorderWidth = null;
let lastBorderHeight = null;
let hasLastBorderBox = false;

class SizesCore {
    initialize() {
        SizesComputedCss.initialize();
        BorderBoxType.initialize();
        PtValsCalcType.initialize(this);
    }

    clearRecursiveSubcallsData() {
        lastRawWidth = null;
        lastRawHeight = null;
        lastBorderWidth = null;
        lastBorderHeight = null;
        hasLastBorderBox = false;
    }

    copyComputedCss(source, target) {
        CloneComputedCss.copyComputedCss(source, target, this);
    }

    // @disablePercentageCSSRecalc 
    //     -> HTML node can't have size with fractional value,
    //     -> so we should supress this calculation in IE8/Safari 5.1.7,etc...
    outerWidth(
        el, 
        includeMargins = false, 
        disablePtCssRecalc = false, 
        disableBordersCalc = false
    ) {
        var elComputedCss = SizesComputedCss.getComputedCss(el);
        var recalcPtCssVals = PtValsCalcType.getIfShouldRecalcPtCssVals(
            el, disablePtCssRecalc
        );
        
        if(elComputedCss.display === "none")
            return 0;

        var computedProps = SizesComputedCss.getComputedProps(
            [
                "paddingLeft", "paddingRight", "marginLeft", "marginRight",
                "borderLeftWidth", "borderRightWidth"
            ], 
            elComputedCss, 
            el
        );
        
        var paddingWidth = computedProps.paddingLeft + computedProps.paddingRight;
        var marginWidth = computedProps.marginLeft + computedProps.marginRight;
        var borderWidth = computedProps.borderLeftWidth + computedProps.borderRightWidth;
        
        // The HTMLElement.offsetWidth read-only property returns the layout width of an element. Typically, 
        // an element's offsetWidth is a measurement which includes the element borders, the element horizontal padding, 
        // the element vertical scrollbar (if present, if rendered) and the element CSS width.
        var outerWidth = 0;
        var normalizedComputedWidth = SizesComputedCss.normalizeComputedCss(
            elComputedCss.width
        );
        
        if(normalizedComputedWidth !== false)
            outerWidth = normalizedComputedWidth;

        var uncomputedElCss = null;
        var parentElWidth = null;
        
        if(recalcPtCssVals) {
            uncomputedElCss = SizesComputedCss.getUncomputedCss(el);
            parentElWidth = this.outerWidth.call(
                this, 
                el.parentNode, 
                false, 
                (el.parentNode.nodeName == "HTML"), 
                true
            );

            if(hasLastBorderBox && 
               PtValsCalcType.hasPtCssVal("width", el, uncomputedElCss))
                parentElWidth -= lastBorderWidth;
        }

        let hasPtPaddings = () => PtValsCalcType.hasPtCssVal(
            ["paddingLeft", "paddingRight"], 
            el, 
            uncomputedElCss
        );
        if(recalcPtCssVals && hasPtPaddings())
            paddingWidth = PtValsCalcType.recalcTwoSidePropPtVals(
                el, parentElWidth, computedProps, uncomputedElCss, "padding"
            );
        
        if(recalcPtCssVals && 
           PtValsCalcType.hasPtCssVal("width", el, uncomputedElCss))
            outerWidth = PtValsCalcType.recalcPtVal(
                el, parentElWidth, uncomputedElCss, "width"
            );

        let borderCheck = () => (
            BorderBoxType.isDefBoxSizing(elComputedCss) && 
            !BorderBoxType.isOuterBoxSizing()
        );
        if(!BorderBoxType.isDefBoxSizing(elComputedCss) || borderCheck()) {
            lastRawWidth = outerWidth;
            outerWidth += paddingWidth;
            if(!disableBordersCalc) outerWidth += borderWidth;
            hasLastBorderBox = false;
        }
        else {
            hasLastBorderBox = true;
            lastRawWidth = outerWidth;
            // If parent el has BorderBox BS, children percentage element width
            // should be calculated without borderWidth. 
            lastBorderWidth = borderWidth;
        }

        if(includeMargins) {
            if(recalcPtCssVals && 
               PtValsCalcType.hasPtCssVal(
                   ["marginLeft", "marginRight"], el, uncomputedElCss
               ))
                marginWidth = PtValsCalcType.recalcTwoSidePropPtVals(
                    el, parentElWidth, computedProps, uncomputedElCss, "margin"
                );

            outerWidth += marginWidth;
        }
        
        return outerWidth;
    }

    // @disablePercentageCSSRecalc 
    //     -> HTML node can't have size with fractional value,
    //     -> so we should supress this calculation in IE8/Safari 5.1.7, etc...
    outerHeight(
        el, 
        includeMargins = false, 
        disablePtCssRecalc = false, 
        disableBordersCalc = false
    ) {
        var elComputedCss = SizesComputedCss.getComputedCss(el);
        var recalcPtCssVals = PtValsCalcType.getIfShouldRecalcPtCssVals(
            el, disablePtCssRecalc, "height"
        );

        if(elComputedCss.display === "none")
            return 0;

        var computedProps = SizesComputedCss.getComputedProps(
            [
                "paddingTop", "paddingBottom", "marginTop", "marginBottom",
                "borderTopWidth", "borderBottomWidth"
            ], 
            elComputedCss, 
            el
        );

        var paddingHeight = computedProps.paddingTop + computedProps.paddingBottom;
        var marginHeight = computedProps.marginTop + computedProps.marginBottom;
        var borderHeight = computedProps.borderTopWidth + computedProps.borderBottomWidth;

        var outerHeight = 0;
        var normalizedComputedHeight = SizesComputedCss.normalizeComputedCss(
            elComputedCss.height
        );

        if(normalizedComputedHeight !== false)
            outerHeight = normalizedComputedHeight;

        var uncomputedElCss = null;
        var parentElWidth = null;
        var parentElHeight = null;

        if(recalcPtCssVals) {
            uncomputedElCss = SizesComputedCss.getUncomputedCss(el);
            parentElWidth = this.outerWidth.call(
                this, 
                el.parentNode, 
                false, 
                (el.parentNode.nodeName == "HTML"), 
                true
            );

            if(hasLastBorderBox)
                parentElWidth -= lastBorderWidth;

            parentElHeight = this.outerHeight.call(
                this, 
                el.parentNode, 
                false, 
                (el.parentNode.nodeName == "HTML"), 
                true
            );

            if(hasLastBorderBox && 
               PtValsCalcType.hasPtCssVal("height", el, uncomputedElCss))
                parentItemHeight -= lastBorderHeight;
        }

        let hasPtPaddings = () => PtValsCalcType.hasPtCssVal(
            ["paddingTop", "paddingBottom"], 
            el, 
            uncomputedElCss
        );
        if(recalcPtCssVals && hasPtPaddings()) {
            paddingHeight = PtValsCalcType.recalcTwoSidePropPtVals(
                el, parentElWidth, computedProps, uncomputedElCss, "padding", true
            );
        }

        if(recalcPtCssVals && 
           PtValsCalcType.hasPtCssVal("height", el, uncomputedElCss))
            outerHeight = PtValsCalcType.recalcPtVal(
                el, parentElHeight, uncomputedElCss, "height"
            );

        let borderCheck = () => (
            BorderBoxType.isDefBoxSizing(elComputedCss) && 
            !BorderBoxType.isOuterBoxSizing()
        );
        if(!BorderBoxType.isDefBoxSizing(elComputedCss) || borderCheck()) {
            lastRawHeight = outerHeight;
            outerHeight += paddingHeight;
            if(!disableBordersCalc) outerHeight += borderHeight;
            hasLastBorderBox = false;
        }
        else {
            hasLastBorderBox = true;
            lastRawHeight = outerHeight;
            // If parentEl has BorderBox BS, children percentage element height
            // should be calculated without borderHeight.
            lastBorderHeight = borderHeight;
        }

        if(includeMargins) {
            if(recalcPtCssVals && 
               PtValsCalcType.hasPtCssVal(
                   ["marginTop", "marginBottom"], el, uncomputedElCss
               )) {
                marginHeight = PtValsCalcType.recalcTwoSidePropPtVals(
                    el, parentElWidth, computedProps, uncomputedElCss, "margin", true
                );
            }

            outerHeight += marginHeight;
        }

        return outerHeight;
    }
};

export default new SizesCore();