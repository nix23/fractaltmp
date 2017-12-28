// @todo -> Add capability to include different 
// vendor file 'builds'??? (Can be different in diff projects???)
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter, Switch, Route, Link } from 'react-router-dom';

import { Provider } from 'react-redux';
import Store from '~/wasm/Store/Store';

import BootWebApp from '~/wasm/BootWeb/App';
import AuthScene from './WebScene/Auth/Auth';
import DashboardScene from './WebScene/Applications/Applications';
import DebugWebModulesScene from '~/wasm/BootWeb/_debug/Scene';

import reducers from './reducers';
const store = Store.getStore(reducers);

class App extends Component {
    render() {
        return (
            <Provider store={store}>
                <div>
                    <BootWebApp>
                        <BrowserRouter>
                            <div>
                                <ul>
                                    <li><Link to='/'>Auth</Link></li>
                                    <li><Link to='/applications'>Applications</Link></li>
                                    <li><Link to='/debugwebmodules'>Debug web modules</Link></li>
                                </ul>

                                <Switch>
                                    <Route exact path='/' component={AuthScene}/>
                                    <Route path='/applications' component={DashboardScene}/>
                                    <Route path='/debugwebmodules' component={DebugWebModulesScene}/>
                                </Switch>
                            </div>
                        </BrowserRouter>
                    </BootWebApp>
                </div>
            </Provider>
        );
    }
}

export default App;