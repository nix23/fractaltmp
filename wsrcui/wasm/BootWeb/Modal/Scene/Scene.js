import React, { Component } from 'react';

import Modal from '~/wasm/BootWeb/Modal/Modal';
import { CSSTransitionGroup } from 'react-transition-group';

import './Scene.css';

export default class ModalScene extends Component {
    render() {
        let content = null;
        if(this.props.render) {
            let className = "WasmBootModalScene__root";

            content = (
                <div 
                    className={className}
                    key={0}
                >
                    <span>Modal Body</span>
                </div>
            );
        }

        return (
            <Modal>
                <CSSTransitionGroup
                    transitionName="WasmBootModalScene__root"
                    transitionEnter={true}
                    transitionEnterTimeout={400}
                    transitionLeave={true}
                    transitionLeaveTimeout={400}>
                    {content}
                </CSSTransitionGroup>
            </Modal>
        );
    }
};