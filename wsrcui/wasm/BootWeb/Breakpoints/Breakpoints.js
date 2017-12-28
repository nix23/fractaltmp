import React, { Component } from 'react';
import { connect } from 'react-redux';

import './Css/Orientation.css';
import './Css/Width.css';
import * as appActions from './Store/actions';

/*
    Use breakpoints like here???
    Or include breakpoints in every component which requires them,
    like in FormSearch???
*/

// Set def value in Store???
class Breakpoints extends Component {
    componentDidMount() {
        this.resolveNextBreakpoints();
        window.addEventListener("resize", this.resolveNextBreakpoints);
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.resolveNextBreakpoints);
    }

    resolveNextBreakpoints = () => {
        this.resolveNextWidthBreakpoint();
        this.resolveNextOrientationBreakpoint();
    }

    resolveNextWidthBreakpoint() {
        this.props.setBreakpoint(
            this.getBreakpointValue(this.widthWatcher)
        );
    }

    resolveNextOrientationBreakpoint() {
        this.props.setOrientationBreakpoint(
            this.getBreakpointValue(this.orientationWatcher)
        );
    }

    getBreakpointValue(watcher) {
        let styles = window.getComputedStyle(watcher, ':after');

        let value = styles.getPropertyValue("content").replace(/"/g, "");
        value = value.replace(/'/g, ""); // Android fix

        return value;
    }

    render() {
        return (
            <div 
                className="WasmBootBreakpoints" 
                ref={(el) => { this.watcher = el; }}
            >
                <div 
                    className="WasmBootBreakpoints__widthWatcher" 
                    ref={(el) => { this.widthWatcher = el; }}
                />
                <div 
                    className="WasmBootBreakpoints__orientationWatcher" 
                    ref={(el) => { this.orientationWatcher = el; }}
                />
            </div>
        );
    }
};

export default connect(
    null,
    (dispatch) => {
        return {
            setBreakpoint: (value) => {
                dispatch(appActions.setBreakpoint(value));
            },
            setOrientationBreakpoint: (orientation) => {
                dispatch(appActions.setOrientationBreakpoint(orientation));
            },
        };
    }
)(Breakpoints);