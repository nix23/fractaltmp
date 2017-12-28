// @todo -> Add capability to include different 
// vendor file 'builds'??? (Can be different in diff projects???)
import React, { Component } from 'react';
import { connect } from 'react-redux';

import './Css/Boilerplate.css';
import './Css/Normalize.css';

import Breakpoints from './Breakpoints/Breakpoints';
import BrowserDetector from './Browser/Detector';
import Console from '~/wasm/Util/Console';
import Dom from './Dom/Dom';
import MeasureSizes from './Dom/Measure/Sizes';
//import Modal from './Modal/Modal';

class App extends Component {
    constructor(props) {
        super(props);

        BrowserDetector.initialize();
        Dom.initialize();
        MeasureSizes.initialize();
    }

    render() {
        // @deprecated <Modal/> after {this.props.children}
        return (
            <div>
                <Breakpoints/>
                <div>Hello from Wasm root!</div>
                {this.props.children}
            </div>
        );
    }
}

// export default App;
const cApp = connect(
    null,
    null
)(App);

export default cApp;