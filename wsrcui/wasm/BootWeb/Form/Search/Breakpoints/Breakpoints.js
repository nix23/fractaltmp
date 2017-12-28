import React, { Component } from 'react';

import './Breakpoints.css';

// @feature -> Dynamic Mobile/Desktop width?
//          -> Connect to Global Breakpoints?
//              -> init in Boot/Web.js -> SPA no need to reinit
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
    }

    resolveNextWidthBreakpoint = () => {
        let nextValue = this.getBreakpointValue(this.widthWatcher);
        if(this.props.breakpointValue == nextValue)
            return;
        
        this.props.onChangeBreakpointValue(nextValue);
    }

    getBreakpointValue = (watcher) => {
        let styles = window.getComputedStyle(watcher, ':after');

        let value = styles.getPropertyValue("content").replace(/"/g, "");
        value = value.replace(/'/g, ""); // Android fix

        return value;
    }

    isMobile = (breakpointValue) => {
        return breakpointValue == "mobile";
    }

    isDesktop = (breakpointValue) => {
        return breakpointValue == "desktop";
    }

    render() {
        return (
            <div 
                className="WasmBootFormSearchBreakpoints" 
                ref={(el) => { this.watcher = el; }}
            >
                <div 
                    className="WasmBootFormSearchBreakpoints__widthWatcher" 
                    ref={(el) => { this.widthWatcher = el; }}
                />
            </div>
        );
    }
};

export default Breakpoints;