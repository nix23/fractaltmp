import React, { Component } from 'react';

import MeasureSizes from '~/wasm/Wasm/Boot/Web/Dom/Measure/Sizes';
import './Overlay.css';

export default class Overlay extends Component {
    constructor(props) {
        super(props);

        this.timeoutOn = null;
        this.timeoutOff = null;
        this.state = {
            display: true,
            render: true,
        };
    }

    componentDidMount() {
        if(!this.props.render) {
            this.setState({
                display: false,
                render: false,
            });
        }
        //MeasureSizes.flushComputedCss(this.root);
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
        this.setState({display: true}, () => {
            clearTimeout(this.timeoutOff);
            //MeasureSizes.flushComputedCss(this.root);
            this.timeoutOn = setTimeout(() => {
                this.setState({render: true});
            }, 20);
        });
    }

    off = () => {
        this.setState({render: false}, () => {
            clearTimeout(this.timeoutOn);
            this.timeoutOff = setTimeout(() => {
                this.setState({display: false});
            }, 500);
        });
    }

    render() {
        let className = "WasmBootModalOverlay__root";
        if(this.state.display)
            className += " WasmBootModalOverlay__root--display";
        if(this.state.render)
            className += " WasmBootModalOverlay__root--render";
        if(this.props.debug)
            className += " WasmBootModalOverlay__root--debug";

        let extraProps = {};
        if(this.props.onClick)
            extraProps.onClick = this.props.onClick;

        return (
            <div 
                className={className}
                ref={(el) => { this.root = el; }}
                {...extraProps}
            >
            </div>
        );
    }
};