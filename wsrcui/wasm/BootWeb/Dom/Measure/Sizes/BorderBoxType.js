import Css from '~/wasm/BootWeb/Css/Css';
import Prefixer from '~/wasm/BootWeb/Dom/Prefixer';
import SizesComputedCss from './ComputedCss';

let prefixedProps = {
    boxSizing: null,
};
const borderBoxTypes = { OUTER: 0, INNER: 1 };
let borderBoxType = null;

class BorderBoxType {
    initialize() {
        this.findPrefixedProps();
        this.findBorderBoxType();
    }

    findPrefixedProps() {
        prefixedProps.boxSizing = Prefixer.get("boxSizing");
    }

    // based on http://connect.microsoft.com/IE/feedback/details/695683/dimensions-returned-by-getcomputedstyle-are-wrong-if-element-has-box-sizing-border-box.
    // At least IE10 and FF7 returns computed width and height without padding and borders, so we should determine sizes calculation type here.
    // Looks like 'workaround', but bootstrap inspired me.(They use similar aproach as in Dom.isBrowserSupportingTransitions
    // to detect if browser is supporting transitions, they are using so-called testerEl).
    findBorderBoxType() {
        var tester = document.createElement("div");

        Css.set(tester, {
            width: "100px",
            padding: "10px 20px",
            borderWidth: "10px 20px",
            borderStyle: "solid",
        });

        var boxSizingProp = prefixedProps.boxSizing;
        tester.style[boxSizingProp] = "border-box";

        var root = document.body || document.documentElement;
        root.appendChild(tester);

        var testerComputedCss = SizesComputedCss.getComputedCss(tester);
        if(SizesComputedCss.normalizeComputedCss(testerComputedCss.width) === 100)
            borderBoxType = borderBoxTypes.OUTER;
        else
            borderBoxType = borderBoxTypes.INNER;

        root.removeChild(tester);
    }

    isDefBoxSizing(itemComputedCss) {
        var boxSizingProp = prefixedProps.boxSizing;
        if(boxSizingProp && itemComputedCss[boxSizingProp]
            && itemComputedCss[boxSizingProp] === "border-box")
            return true;

        return false;
    }

    isOuterBoxSizing() {
        return borderBoxType === borderBoxTypes.OUTER;
    }
};

export default new BorderBoxType();