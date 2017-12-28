import React, { Component } from 'react';

import Attr from '~/wasm/BootWeb/Dom/Attr';
import BrowserDetector from '~/wasm/BootWeb/Browser/Detector';
import Css from '~/wasm/BootWeb/Css/Css';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Util from '~/wasm/Util/Util';

import KeyEvents from './KeyEvents';
import MobileInputOverlay from './InputOverlay';

const ON_INPUT_CHANGE_DELAY = 100;

const PreventAutocomplete = (props) => {
    return (
       <div style={{display: "none"}}>
            <input className="preventChromeAutoComplete"
                   name="preventAc" 
                   autoComplete="prevent-ac"/>
        </div>
    );
};

export default class Input extends Component {
    constructor(props) {
        super(props);

        this.keyEvents = new KeyEvents(this);
        this.preventFetchDataOnNextFocus = false;
        this.nextOnInputChangeTimeout = null;

        this.state = {
            isInputFocused: false,
            isInputOverlayHover: false,
        };
    }

    componentDidMount() {
        Attr.rm(this.input, "disabled");
    }

    // Congruent to onFoundData -> processEnter in prev Version
    componentWillReceiveProps(nextProps) {
        if(nextProps.items !== this.props.items)
            this.keyEvents.onEnterPress(
                this.props.searchValue, this.props
            );
    }

    setUnfocusedInputState = () => {
        this.setState({isInputFocused: false});
    }

    focus = () => {
        this.input.focus();
    }

    blur = () => {
        this.input.blur();
    }

    onInputOverlayMouseEnter = () => {
        this.setState({isInputOverlayHover: true});
    }

    onInputOverlayMouseLeave = () => {
        this.setState({isInputOverlayHover: false});
    }

    onInputOverlayClick = (ev) => {
        this.props.openModalForm();
        this.onSyntheticInputKeyUp(ev);

        if(BrowserDetector.isIos())
            this.props.focusMobileInput();
    }

    onSyntheticInputKeyUp = (event) => {
        this.onInputKeyUp(event, true);
    }

    onInputKeyUp = (event, isSyntheticEvent = false) => {
        if(!isSyntheticEvent)
            this.keyEvents.onKeyDown(event, this.props, this);
        this.props.loadingOn();

        // Example: onOverlayClick
        if(typeof event.target === "undefined" || 
           typeof event.target.value === "undefined") {
            this.scheduleNextOnInputChange(this.props.searchValue);
            return;
        }

        let nextValue = event.target.value;
        this.props.setSearchValue(nextValue, () => {
            this.scheduleNextOnInputChange(nextValue);
        });
    }

    scheduleNextOnInputChange = (nextValue) => {
        clearTimeout(this.nextOnInputChangeTimeout);
        this.nextOnInputChangeTimeout = setTimeout(() => {
            this.onInputChange(nextValue);
        }, ON_INPUT_CHANGE_DELAY);
    }

    onInputChange = (nextValue) => {
        this.props.execSearch(nextValue);
    }

    onInputFocus = (ev) => {
        this.setState({isInputFocused: true});
        if(this.preventFetchDataOnNextFocus) {
            this.preventFetchDataOnNextFocus = false;
            return;
        }

        this.onSyntheticInputKeyUp(ev);
    }

    isInputFocused = () => {
        return this.state.isInputFocused;
    }

    onInputBlur = () => {
        this.setState({isInputFocused: false});
    }

    // @todo -> Check if onChange implements this
    onInputPaste = (ev) => {
        setTimeout(() => this.onSyntheticInputKeyUp(ev), 20);
    }

    onInputCut = (ev) => {
        setTimeout(() => this.onSyntheticInputKeyUp(ev), 20);
    }

    getInputWidth = () => {
        return MeasureSizes.outerWidth(this.input);
    }

    render() {
        let className = "";
        if(this.state.isInputOverlayHover)
            className += "WasmBootFormSearch__inputHover";
        if(this.state.isInputFocused)
            className += " WasmBootFormSearch__inputFocused";

        let inputJsx = (
            <input 
                type="text"
                placeholder="Enter value"
                value={this.props.searchValue}
                disabled="disabled"
                onChange={this.onInputKeyUp}
                onFocus={this.onInputFocus}
                onBlur={this.onInputBlur}
                onPaste={this.onInputPaste}
                onCut={this.onInputCut}
                ref={(el) => { this.input = el; }}
                className={className}
            />
        );
        if(this.props.inputWithMobileOverlay)
            inputJsx = (
                <MobileInputOverlay
                    {...this.props}
                    onMouseEnter={this.onInputOverlayMouseEnter}
                    onMouseLeave={this.onInputOverlayMouseLeave}
                    onClick={this.onInputOverlayClick}
                >
                    {inputJsx}
                </MobileInputOverlay>
            );

        return (
            <form autoComplete="off">
                <PreventAutocomplete/>
                {inputJsx}
            </form>
        );
    }
};