import React, { Component } from 'react';

import DebugGridSlider from '~/wasm/BootWeb/_debug/Grid/Slider/View';
import DebugFormSearch from '~/wasm/BootWeb/_debug/Form/Search/View';
import DebugModalScene from '~/wasm/BootWeb/_debug/Modal/Scene/View';

const MODULES = {
    "GridSlider": DebugGridSlider,
    "FormSearch": DebugFormSearch,
    "ModalScene": DebugModalScene,
};

class Scene extends Component {
    constructor(props) {
        super(props);
        this.state = {
            module: Object.keys(MODULES)[0],
        };
    }

    render() {
        return (
            <div>
                <div>Select module for debug</div>
                {this.renderMenu()}
                {this.renderModule()}
            </div>
        );
    }

    renderMenu = () => {
        return (
            <ul>
                {Object.keys(MODULES).map((moduleName, i) => {
                    return (
                        <li key={i} onClick={() => this.setState({module: moduleName})}>
                            <a href="#">{moduleName}</a>
                        </li>
                    );
                })}
            </ul>
        );
    }

    renderModule = () => {
        let Component = MODULES[this.state.module];
        return (
            <Component/>
        );
    }
}

export default Scene;