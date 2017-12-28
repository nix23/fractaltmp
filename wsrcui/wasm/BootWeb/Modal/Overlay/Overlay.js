import React, { Component } from 'react';

import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Modal from '~/wasm/BootWeb/Modal/Modal';
import { CSSTransitionGroup } from 'react-transition-group';

import './Overlay.css';

export default class ModalOverlay extends Component {
    render() {
        let extraProps = {};
        if(this.props.onClick)
            extraProps.onClick = this.props.onClick;

        let content = null;
        if(this.props.render) {
            let className = "WasmBootModalOverlay__root";
            if(this.props.rootInsideEl)
                className += " WasmBootModalOverlay__rootInsideEl";

            content = (
                <div 
                    className={className}
                    key={0}
                    {...extraProps}
                >
                </div>
            );
        }

        return (
            <Modal>
                <CSSTransitionGroup
                    transitionName="WasmBootModalOverlay__root"
                    transitionEnter={true}
                    transitionEnterTimeout={500}
                    transitionLeave={true}
                    transitionLeaveTimeout={500}>
                    {content}
                </CSSTransitionGroup>
            </Modal>
        );
    }
};

export const ElementOverlay = (props) => {
    return (
        <ModalOverlay {...props} rootInsideEl/>
    );
};