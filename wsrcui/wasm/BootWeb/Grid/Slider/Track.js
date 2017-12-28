import Css from '~/wasm/BootWeb/Css/Css';
import Css3 from '~/wasm/BootWeb/Css/Css3';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Util from '~/wasm/Util/Util';

import Slides from './Slides';

class Track {
    updateOffsetsX(slides, activeSlide, restoreTransitionTimeouts, duration) {
        restoreTransitionTimeouts.map((tm) => clearTimeout(tm));
        restoreTransitionTimeouts = [];
        
        let offsets = Slides.getOffsetsX(slides, activeSlide);
        offsets.map((offset, i) => {
            restoreTransitionTimeouts.push(
                Slides.setOffsetX(
                    slides[i],
                    offset,
                    duration
                )
            );
        });

        return restoreTransitionTimeouts;
    }

    slideTo(slides, activeSlide) {
        let offsets = Slides.getOffsetsX(
            slides, activeSlide
        );
        offsets.map((offset, i) => {
            Css3.transformProp(
                slides[i],
                "translate3d",
                offset + "px,0px,0px"
            );
        });
    }

    nextSlide(slides, activeSlide, props) {
        activeSlide++;
        if(activeSlide >= props.items.length)
            activeSlide = 0;

        return activeSlide;
    }

    prevSlide(slides, activeSlide, props) {
        activeSlide--;
        if(activeSlide < 0)
            activeSlide = props.items.length - 1;

        return activeSlide;
    }
};

export default new Track();