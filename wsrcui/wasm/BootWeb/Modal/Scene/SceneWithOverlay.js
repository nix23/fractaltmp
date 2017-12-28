import React, { Component } from 'react';

import ModalScene from '~/wasm/BootWeb/Modal/Scene/Scene';
import Overlay from '~/wasm/BootWeb/Modal/Overlay/Overlay';

export default class SceneWithOverlay extends Component {
    render() {
        let overlayProps = {};
        if(this.props.onOverlayClick)
            overlayProps.onClick = this.props.onOverlayClick;
        
        let sceneProps = {};

        return [
            <Overlay 
                {...this.props}
                {...overlayProps}
                key="viewportOverlay"
            />,
            <ModalScene 
                {...this.props}
                {...sceneProps}
                key="modalScene"
            />
        ];
    }
};