import React, { Component } from 'react';
import BrowserDetector from '~/wasm/BootWeb/Browser/Detector';

import './RootDomEl.css';

const OPEN_MOBILE_KEYBOARD_DELAY = 320;

class RootDomEl extends Component {
    constructor(props) {
        super(props);

        this.timeouts = {
            on: null,
            onMobileFormUi: null,
            onMobileKeyboard: null,
            off: null,
            offMobileInputBlur: null,
        };
        this.state = {
            display: false,
            render: false,
        };
    }

    componentWillReceiveProps(nextProps) {
        if(nextProps.isOn === this.props.isOn)
            return;

        this.clearTimeouts();

        if(nextProps.isOn)
            this.on();
        else
            this.off();
    }

    getRoot = () => {
        return this.root;
    }

    clearTimeouts = () => {
        for(let prop in this.timeouts)
            clearTimeout(this.timeouts[prop]);
    }

    scheduleOpenKeyboard = () => {
        if(this.props.isOn || BrowserDetector.isIos())
            return;

        // Ios allows to focus only inside onclick event
        // (This case is used for all other platforms)
        this.timeouts.onMobileKeyboard = setTimeout(() => {
            this.props.focusMobileInput();
        }, OPEN_MOBILE_KEYBOARD_DELAY);
    }

    on = () => {
        this.setState({display: true});

        this.timeouts.onMobileFormUi = setTimeout(() => {
            this.props.setIsMobileFormUiOn(true);
        }, 500);

        this.timeouts.on = setTimeout(() => {
            this.setState({render: true});
        }, (BrowserDetector.isIos()) ? 50 : 20);
    }

    off = () => {
        clearTimeout(this.timeouts.onMobileKeyboard);
        this.props.blurMobileInput();

        // Always use setTimeout here with small delay
        //     -> Even with no animation, otherwise field will be 
        //        refocused on Android and form will stick open 
        //        on result row selects
        this.timeouts.off = setTimeout(() => {
            this.setState({display: false});
            this.props.setIsMobileFormUiOn(false);
        }, 470);

        // Keyboard blur delay(or animation will be slow)
        this.timeouts.offMobileInputBlur = setTimeout(() => {
            this.setState({render: false});
        }, 150);
    }

    render() {
        let css = {
            width: this.props.rootWidth + "px",
            height: this.props.rootHeight + "px",
            left: this.props.rootLeft + "px",
            top: this.props.rootTop + "px",
        };
        let className = "WasmBootFormSearch__root";
        if(this.state.display)
            className += " WasmBootFormSearch__root--display";
        if(this.state.render)
            className += " WasmBootFormSearch__root--render";
        // @feature -> formRootClass prop

        return (
            <div 
                className={className}
                ref={(el) => { this.root = el; }}
                style={css}
            >
                {this.props.children}
            </div>
        );
    }
};

export default RootDomEl;