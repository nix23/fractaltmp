import Css from '~/wasm/BootWeb/Css/Css';
import Css3 from '~/wasm/BootWeb/Css/Css3';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Prefixer from '~/wasm/BootWeb/Dom/Prefixer';

class Slides {
    init(slides, duration) {
        let nextTranslateX = 0;

        slides.map((slide) => {
            Css.set(slide, {
                position: "absolute",
                left: "0px",
                top: "0px",
            });

            Css3.transition(
                slide,
                Prefixer.getForCss('transform', slide) + " 0ms ease"
            );
            Css3.transform(
                slide,
                "translate3d(" + nextTranslateX + "px,0px,0px)"
            );

            // Old -> outerWidth(slide) + 1?
            nextTranslateX += MeasureSizes.outerWidth(slide);
            this.setTransformTransition(slide, duration);
        });
    }

    showAll(slides) {
        slides.map((slide) => {
            Css.set(slide, {
                visibility: "visible",
            });
        });
    }

    setOffsetX(slide, offsetX, duration) {
        // Old -> Prefixer.getForCss('transform', el) + " 0ms ease"
        Css3.transition(slide, "");
        Css3.transformProp(
            slide,
            "translate3d",
            offsetX + "px,0px,0px"
        );

        return setTimeout(() => {
            this.setTransformTransition(slide, duration);
        }, 100);
    }

    getOffsetsX(slides, activeSlide) {
        let offsets = [];
        let nextOffset = 0;

        slides.map((slide) => {
            offsets.push(nextOffset);
            nextOffset += MeasureSizes.outerWidth(slide);
        });
        
        if(activeSlide == 0)
            return offsets;

        for(let i = 1; i <= activeSlide; i++) 
            for(let j = 0; j < offsets.length; j++)
                offsets[j] -= MeasureSizes.outerWidth(slides[j]);

        return offsets;
    }

    setTransformTransition(slide, duration) {
        Css3.transitionProp(
            slide,
            Prefixer.getForCss('transform', slide) + " " + duration + "ms ease"
        );
    }
};

export default new Slides();