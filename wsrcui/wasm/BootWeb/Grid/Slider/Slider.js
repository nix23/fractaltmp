// @todo -> Transform to WMOD???
import React, { Component } from 'react';

import Css from '~/wasm/BootWeb/Css/Css';
import Css3 from '~/wasm/BootWeb/Css/Css3';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Util from '~/wasm/Util/Util';

import Nav from './Nav';
import Slides from './Slides';
import Root from './Root';
import Track from './Track';
import './Slider.css';

const DURATION = 400;
const TIMEOUT = 4000;

// @todo -> Check Ref when item is removed from DOM
// @todo -> Move translate3d to SlidesContainer item???
//       -> 1 translate instead of N translates.
// @todo -> Set min container height??? (Before JS will execute)
// @todo -> Add breakpoints to MIN/MAX height???(Read from Breakpoints.js)
// @todo -> Add themes support???
class Slider extends Component {
    constructor(props) {
        super(props);

        this.slides = [];
        this.restoreTransitionTimeouts = [];
        this.showNextSlideTimeout = null;

        this.state = {
            activeSlide: 0,
        };
    }

    componentDidMount() {
        window.addEventListener("resize", this.onResize);

        Slides.init(this.slides, this.getDuration());
        Slides.showAll(this.slides);
        this.setRootHeight();
        this.rescheduleShowNext();
    }

    componentDidUpdate() {
        this.slides.filter((el) => el != null);
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.onResize);
        clearTimeout(this.showNextSlideTimeout);
    }

    // shouldComponentUpdate(nextProps, nextState) {
    //     this.Nav.setState({activeSlide: nextState.activeSlide}, () => {
    //         this.Nav.forceUpdate();
    //     });
    //     return (nextProps.items !== this.props.items);
    // }

    onResize = () => {
        this.updateOffsetsX();
        this.setRootHeight();
    }

    setRootHeight = () => {
        Root.setHeightSameAsInActiveSlide(
            this.slides, this.state.activeSlide, this.root, this.props
        );
    }

    updateOffsetsX = () => {
        this.restoreTransitionTimeouts = Track.updateOffsetsX(
            this.slides, 
            this.state.activeSlide, 
            this.restoreTransitionTimeouts, 
            this.getDuration()
        );
    }

    getDuration = () => {
        return Util.getIfDefined(this.props, "duration", DURATION);
    }

    getTimeout = () => {
        return Util.getIfDefined(this.props, "timeout", TIMEOUT);
    }

    slideTo = (slide) => {
        this.setState({activeSlide: slide}, () => {
            this.setRootHeight();

            Track.slideTo(this.slides, this.state.activeSlide);
            this.rescheduleShowNext();
        });
    }

    slideToNext = () => {
        this.slideTo(Track.nextSlide(
            this.slides, this.state.activeSlide, this.props
        ));
    }

    slideToPrev = () => {
        this.slideTo(Track.prevSlide(
            this.slides, this.state.activeSlide, this.props
        ));
    }

    rescheduleShowNext = () => {
        clearTimeout(this.showNextSlideTimeout);
        this.showNextSlideTimeout = setTimeout(() => { 
            this.slideToNext();
        }, this.getTimeout());
    }

    render() {
        // @todo -> On NavItem click/touch -> 
        //      clearShowNextTimeout(); slideTo(I);
        // @todo -> Add next/prev buttons to Nav
        //       -> onCl clearShowNextTm(); slideToNext||slideToPrev
        return (
            <div className="WasmBootGridSlider__root"
                 ref={(el) => { this.root = el; }}>
                <div className="WasmBootGridSlider__slides"
                    ref={(el) => { this.slidesRoot = el; }}>
                    {this.props.items.map(this.renderItem)}
                </div>
                <Nav 
                    ref={(C) => { this.Nav = C; }}
                    items={this.props.items}
                    activeItem={this.state.activeSlide}
                />
            </div>
        );
    }

    renderItem = (item, i) => {
        return (
            <div className="WasmBootGridSlider__slide"
                 key={item.id}
                 ref={(el) => { this.slides[i] = el; }}>
                {this.props.renderItem(item)}
            </div>
        );
    }
};

export default Slider;