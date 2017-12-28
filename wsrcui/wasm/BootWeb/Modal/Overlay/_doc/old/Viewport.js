import React, { Component } from 'react';

import Modal from '~/wasm/Wasm/Boot/Web/Modal/Modal';
import Overlay from './Overlay';

import './Viewport.css';

export default class ViewportOverlay extends Component {
    constructor(props) {
        super(props);

        this.timeoutOff = null;
        this.state = {
            render: true,
        };
    }

    componentDidMount() {
        if(!this.props.render) 
            this.setState({render: false});
    }

    componentWillReceiveProps(nextProps) {
        if(nextProps.render == this.state.render)
            return;

        if(nextProps.render)
            this.on();
        else
            this.off();
    }

    on = () => {
        clearTimeout(this.timeoutOff);
        this.setState({render: true});
    }

    off = () => {
        this.timeoutOff = setTimeout(() => {
            this.setState({render: false});
        }, 520);
    }

    render() {
        let className = "WasmBootModalOverlay__viewportRoot";
        if(this.state.render)
            className += " WasmBootModalOverlay__viewportRoot--display";

        return (
            <Modal>
                <div className={className}>
                    <Overlay
                        {...this.props}
                    />
                </div>
            </Modal>
        );
    }
};