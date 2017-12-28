import MeasureSizes from './Sizes';

class Offset {
    offsetLeft(el) {
        var clientRect = el.getBoundingClientRect();
        var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        return clientRect.left + scrollLeft;
    }

    offsetTop(el) {
        var clientRect = el.getBoundingClientRect();
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        return clientRect.top + scrollTop;
    }

    offset(el, substractMargins = false, outerFnName, offsetFnName) {
        if(substractMargins) {
            var elemSize = MeasureSizes[outerFnName](el);
            var elemSizeWithMargins = MeasureSizes[outerFnName](el, true);
            var marginSize = elemSizeWithMargins - elemSize;
            var halfOfMarginSize = marginSize / 2;
            var sideOffset = this[offsetFnName](el) - halfOfMarginSize;
        }
        else
            var sideOffset = this[offsetFnName](el);

        return sideOffset;
    }

    left(el, substractMargins) {
        return this.offset(el, substractMargins, "outerWidth", "offsetLeft");
    }

    top(el, substractMargins) {
        return this.offset(el, substractMargins, "outerHeight", "offsetTop");
    }
};

export default new Offset();