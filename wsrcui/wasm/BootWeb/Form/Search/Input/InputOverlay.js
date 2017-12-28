import React, { Component } from 'react';

import './InputOverlay.css';

class MobileInputOverlay extends Component {
    render() {
        // needsclick can be removed if FastClick will not be used
        return (
            <div className="WasmBootFormSearch__mobileInputOverlayRoot needsclick">
                {this.props.children}
                {this.renderOverlay()}
            </div>
        );
    }

    renderOverlay = () => {
        if(!this.props.isMobile)
            return null;

        // @feature -> Render transp.ent overlay if IS_DEV
        let className = "WasmBootFormSearch__mobileInputOverlay";
        if(this.props.debugOverlay)
            className += " WasmBootFormSearch__mobileInputOverlay--debug";

        // We should allow ghost clicks in IOS - 
        // otherwise keyboard will not show on focus()
        return (
            <div 
                className={className}
                onMouseEnter={this.props.onMouseEnter}
                onMouseLeave={this.props.onMouseLeave}
                onClick={this.props.onClick}
                ref={(el) => { this.overlay = el; }}
            />
        );
    }
};

export default MobileInputOverlay;