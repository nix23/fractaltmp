import Css from '~/wasm/BootWeb/Css/Css';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Util from '~/wasm/Util/Util';

class Root {
    setHeightSameAsInActiveSlide(slides, activeSlide, root, props) {
        let nextHeight = MeasureSizes.outerHeight(
            slides[activeSlide]
        ); 

        let minHeight = Util.getIfDefined(props, "minHeight", null);
        let maxHeight = Util.getIfDefined(props, "maxHeight", null);

        if(minHeight != null && nextHeight < minHeight)
            nextHeight = minHeight;
        if(maxHeight != null && nextHeight > maxHeight)
            nextHeight = maxHeight;

        Css.set(root, {height: nextHeight + "px"});
    }
};

export default new Root();