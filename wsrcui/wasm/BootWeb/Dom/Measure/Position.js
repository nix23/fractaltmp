import SizesComputedCss from './Sizes/ComputedCss';

class Position {
    positionLeft(el) {
        var elComputedCss = SizesComputedCss.getComputedCss(el);
        if(elComputedCss.display == "none")
            return 0;

        var computedProps = SizesComputedCss.getComputedProps(
            ["marginLeft"], elComputedCss, el
        );
        return el.offsetLeft - computedProps.marginLeft;
    }

    positionTop(el) {
        var elComputedCss = SizesComputedCss.getComputedCss(el);
        if(elComputedCss.display == "none")
            return 0;

        var computedProps = SizesComputedCss.getComputedProps(
            ["marginTop"], elComputedCss, el
        );
        return el.offsetTop - computedProps.marginTop;
    }
};

export default new Position();