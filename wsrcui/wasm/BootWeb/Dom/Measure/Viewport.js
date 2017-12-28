import MeasureSizes from './Sizes';

class Viewport {
    getVisibleHeight(el) {
        var elH = MeasureSizes.outerHeight(el);
        var H = this.height();
        var r = el.getBoundingClientRect();
        var t = r.top;
        var b = r.bottom;

        if(t > 0)
            var res = Math.min(elH, H-t);
        else
            var res = (b < H  ? b : H);

        return Math.max(0, res);
    }

    width() {
        return document.documentElement.clientWidth;
    }

    height() {
        return document.documentElement.clientHeight;
    }

    scrollLeft() {
        return window.pageXOffset || document.documentElement.scrollLeft;
    }

    scrollTop() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }

    viewportDocumentCoords() {
        return {
            x1: this.scrollLeft(),
            x2: this.scrollLeft() + this.width() - 1,
            y1: this.scrollTop(),
            y2: this.scrollTop() + this.height() - 1
        };
    }
};

export default new Viewport();