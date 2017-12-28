// @todo -> Add capability to include different 
// vendor file 'builds'??? (Can be different in diff projects???)
// @todo -> Merge this file into withApp() HOF?
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import WebRoot from './WebRoot';

ReactDOM.render(
    <WebRoot/>,
    document.getElementById('WasmAppRoot')
);

if(module.hot) {
    module.hot.accept('./WebRoot', () => {
        const NextAppState = require('./WebRoot').default;
        ReactDOM.render(<NextAppState/>, document.getElementById('WasmAppRoot'));
    });
}