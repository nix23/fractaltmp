import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';

class ScrollData {
    constructor(FormData) {
        this.FormData = FormData;
    }

    scrollTo(items, itemToScroll, props) {
        let rootHeight = MeasureSizes.outerHeight(this.FormData.dataRoot);
        let viewport = {
            y1: this.FormData.data.scrollTop,
            y2: this.FormData.data.scrollTop + rootHeight,
        };
        let coords = this.getNextScrollCoords(items, itemToScroll);

        if(coords.y2 > viewport.y2 && !props.wasFocusedWithKeyUp)
            this.FormData.data.scrollTop = coords.y2 - rootHeight;
        else if(coords.y1 < viewport.y1 && props.wasFocusedWithKeyUp)
            this.FormData.data.scrollTop = coords.y1;
    }

    getNextScrollCoords(items, itemToScroll) {
        let nextCoords = {
            y1: 0,
            y2: 0,
        };

        let lastItemHeight = 0;
        let itemFound = false;

        items.map((item) => {
            if(itemFound) return;
            lastItemHeight = MeasureSizes.outerHeight(item);

            nextCoords.y1 += lastItemHeight;
            nextCoords.y2 = nextCoords.y1 + lastItemHeight - 1;

            if(item === itemToScroll)
                itemFound = true;
        });

        return nextCoords;
    }
};

export default ScrollData;