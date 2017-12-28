import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Modal extends Component {
    constructor(props) {
        super(props);

        this.modalRoot = document.getElementById('WasmAppModalRoot');
        this.modalInstanceRoot = document.createElement('div');
    }

    componentDidMount() {
        this.modalRoot.appendChild(this.modalInstanceRoot);
    }

    componentWillUnmount() {
        this.modalRoot.removeChild(this.modalInstanceRoot);
    }

    render() {
        return ReactDOM.createPortal(
            this.props.children,
            this.modalInstanceRoot
        );
    }
};