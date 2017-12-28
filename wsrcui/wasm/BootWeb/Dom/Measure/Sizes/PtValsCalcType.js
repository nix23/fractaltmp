import Css from '~/wasm/BootWeb/Css/Css';
import SizesComputedCss from './ComputedCss';
import SizesValidator from './Validator';

const ptValsCalcTypes = { BROWSER: 0, RECALC: 1 };
let ptValsCalcType = null;

class PtValsCalcType {
    initialize(SizesCore) {
        this.findPtValsCalcType(SizesCore);
    }

    findPtValsCalcType(SizesCore) {
        let testerWrap = document.createElement("div");
        let tester = document.createElement("div");

        Css.set(testerWrap, {
            width: "1178px",
            height: "300px",
            position: "absolute",
            left: "-9000px",
            top: "0px",
            visibility: "hidden",
        });

        var root = document.body || document.documentElement;
        root.appendChild(testerWrap);

        Css.set(tester, {
            width: "10%",
            height: "200px"
        });
        testerWrap.appendChild(tester);

        var expectedOw = 117.796875.toFixed(1);
        var ow = parseFloat(SizesCore.outerWidth(tester, true, true)).toFixed(1);
        ptValsCalcType = (expectedOw == ow) 
            ? ptValsCalcTypes.BROWSER 
            : ptValsCalcTypes.RECALC;
        
        root.removeChild(testerWrap);
    }

    areBrowserPtVals() {
        return ptValsCalcType == ptValsCalcTypes.BROWSER;
    }

    areRecalcPtVals() {
        return ptValsCalcType == ptValsCalcTypes.RECALC;
    }

    hasPtCssVal(cssProp, el, elComputedCss) {
        var tester = (cssProp, el, elComputedCss) => {
            SizesValidator.ensureHasParentNode(el);

            elComputedCss = elComputedCss || SizesComputedCss.getUncomputedCss(el);
            SizesValidator.ensureHasComputedProp(elComputedCss, cssProp);

            var ptValRegex = new RegExp("(.*\\d)%$");
            return ptValRegex.test(elComputedCss[cssProp]);
        };

        if(Array.isArray(cssProp)) {
            for(var i = 0; i < cssProp.length; i++) {
                if(tester.call(this, cssProp[i], el, elComputedCss))
                    return true;
            }

            return false;
        }
        else
            return tester.call(this, cssProp, el, elComputedCss);
    }

    getPtCssVal(cssProp, el, elComputedCss) {
        SizesValidator.ensureHasParentNode(el);

        elComputedCss = elComputedCss || SizesComputedCss.getUncomputedCss(el);
        SizesValidator.ensureHasComputedProp(elComputedCss, cssProp);

        return elComputedCss[cssProp];
    }

    recalcPtVal(el, parentElSize, uncomputedProps, propName) {
        var ptSize = parseFloat(this.getPtCssVal(propName, el, uncomputedProps));
        return parentElSize / 100 * ptSize;
    }

    recalcTwoSidePropPtVals(
        el,
        parentElWidth,
        computedProps,
        uncomputedProps,
        cssPropPrefix,
        verticalSides
    ) {
        var firstSideProp = cssPropPrefix + ((verticalSides) ? "Top" : "Left");
        var secondSideProp = cssPropPrefix + ((verticalSides) ? "Bottom" : "Right");
        var firstSideVal = computedProps[firstSideProp];
        var secondSideVal = computedProps[secondSideProp];

        if(this.hasPtCssVal(firstSideProp, el, uncomputedProps))
            firstSideVal = this.recalcPtVal(
                el, parentElWidth, uncomputedProps, firstSideProp
            );
        if(this.hasPtCssVal(secondSideProp, el, uncomputedProps))
            secondSideVal = this.recalcPtVal(
                el, parentElWidth, uncomputedProps, secondSideProp
            );

        return firstSideVal + secondSideVal;
    }

    getIfShouldRecalcPtCssVals(el, disablePtCssRecalc, dimension = "width") {
        let recalcPtCssVals;

        if(disablePtCssRecalc || this.areBrowserPtVals())
            recalcPtCssVals = false;
        else if(this.areRecalcPtVals()) {
            SizesValidator.ensureHasParentNode(el);
            recalcPtCssVals = this.hasPtCssVal(dimension, el);
        }

        return recalcPtCssVals;
    }
};


export default new PtValsCalcType();