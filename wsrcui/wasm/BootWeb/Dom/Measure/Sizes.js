// @todo -> Implement
import Dom from '~/wasm/BootWeb/Dom/Dom';
import SizesCore from './Sizes/Core';
import SizesComputedCss from './Sizes/ComputedCss';

class MeasureSizes {
    initialize() {
        SizesCore.initialize();
    }

    copyComputedCss = (source, target) => SizesCore.copyComputedCss(source, target)
    getComputedCss = (el) => SizesComputedCss.getComputedCss(el)
    flushComputedCss = (el) => SizesComputedCss.flushComputedCss(el)
    outerWidth = (el, margins = false) => {
        let result = SizesCore.outerWidth(el, margins);
        SizesCore.clearRecursiveSubcallsData();

        return result;
    }
    outerHeight = (el, margins = false) => {
        let result = SizesCore.outerHeight(el, margins);
        SizesCore.clearRecursiveSubcallsData();

        return result;
    }
};

export default new MeasureSizes();