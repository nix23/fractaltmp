import React, { Component } from 'react';

import './Controls.css';
import Css from '~/wasm/BootWeb/Css/Css';
import Input from '../Input/Input';

const CloseButton = (props) => {
    return (
        <div className="WasmBootFormSearch__closeButtonRoot"
            onClick={props.onClose}
        >
            <div className="WasmBootFormSearch__closeButton">
                close
            </div>
        </div>
    );
};

class MobileInput extends Component {
    isFocused = () => {
        return this.Input.isInputFocused();
    }

    focus = () => {
        this.Input.focus();
    }

    blur = () => {
        this.Input.blur();
    }

    setUnfocusedInputState = () => {
        this.Input.setUnfocusedInputState();
    }

    onClick = () => {
        this.focus();
    }

    // @todo -> MobileInputDecorator (Wraps <Input/>)
    //       -> By def <div w=100 h=100?>
    render() {
        return (
            <div className="WasmBootFormSearch__mobileInputRoot"
                 onClick={this.onClick}
            >
                <div className="WasmBootFormSearch__mobileInput">
                    <Input 
                        ref={(C) => { this.Input = C; }}
                        {...this.props}
                    />
                </div>
            </div>
        );
    }
};

class MobileControls extends Component {
    focusMobileInput() {
        if(this.MobileInput.isFocused())
            return;
        this.MobileInput.focus();
    }

    blurMobileInput() {
        this.MobileInput.blur();
    }

    onCloseButtonClose = () => {
        if(!this.props.isMobileFormUiOn())
            return;

        this.props.closeForm();
    }

    setUnfocusedInputState = () => {
        this.MobileInput.setUnfocusedInputState();
    }

    render() {
        return (
            <div className="WasmBootFormSearch__mobileControls">
                <CloseButton
                    onClose={this.onCloseButtonClose}
                />
                <MobileInput
                    ref={(C) => { this.MobileInput = C; }}
                    {...this.props}
                />
            </div>
        );
    }
};

export default MobileControls;