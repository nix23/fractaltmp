import { createStore, applyMiddleware, compose, combineReducers } from 'redux';
import createSagaMiddleware from 'redux-saga';
import thunk from 'redux-thunk';

// import { createNavigationEnabledStore } from '@expo/ex-navigation';
import createLogger from 'redux-logger';

//import Refs from '~/boot/service/Refs';

// const createExNavStore = createNavigationEnabledStore({
//     createStore,
//     navigationStateKey: 'navigation',
// });

//import sagas from '~/wasm/Cmf/WebScene/Auth/sagas';

const sagaMiddleware = createSagaMiddleware();
const logger = createLogger();
const thunkArgs = {
    //refs: Refs,
};

const Reducers = {
    root: null,

    combine(reducers) {
        if(this.root == null)
            this.root = this.recombine(reducers);

        return this.root;
    },

    recombine(reducers) { 
        return combineReducers(reducers);
    }, 
};

const createStoreInstance = (reducers, initialState) => {
    const middlewares = [thunk.withExtraArgument(thunkArgs), logger, sagaMiddleware];
    //const middlewares = [thunk.withExtraArgument(thunkArgs)];
    
    const store = createStore(
        Reducers.combine(reducers), 
        initialState, 
        applyMiddleware(...middlewares)
    );
    //sagaMiddleware.run(sagas);
    //store.dispatch({type: "test"});
    //store.subscribe(() => console.log(store.getState()));

    // if(module.hot) {
    //     module.hot.accept(() => {
    //         const nextRootReducer = require('../reducer').default.getFreshRootReducer();
    //         store.replaceReducer(nextRootReducer);
    //     });
    // }

    return store;
};

const Store = {
    store: null,

    getStore(reducers = {}) {
        if(this.store == null)
            this.store = createStoreInstance(reducers);

        return this.store;
    }
};

export default Store;