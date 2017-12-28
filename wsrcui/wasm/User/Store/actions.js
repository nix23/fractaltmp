import Api from '~/wasm/Api/Api';
import UserStore from './User';
import moment from 'moment';

import * as accountDataRefetchActions from '~/data/account/data/refetch/actions';
import * as formActions from '~/data/form/actions';

// @todo -> ADMIN_USER, APP_USER 1 or array of authorized users????
//       -> Store app name for each loggedIn user??
//       -> By default WASM_CMF user???
//       -> Or user -> array of loggedIn Apps?(Backend+Frontend)
export const actions = {
    SET_USER: "wasm_user/SET_USER",
    SET_LOGGED_IN: "wasm_user/SET_LOGGED_IN",
    SET_LOGGED_OUT: "wasm_user/SET_LOGGED_OUT",
    SET_ORIENTATION: "wasm_user/SET_ORIENTATION",
    SET_LAST_ACTIVITY: "wasm_user/SET_LAST_ACTIVITY",
    SET_PUSH_TOKEN_DATA: "wasm_user/SET_PUSH_TOKEN_DATA",
    SAVE_PUSH_TOKEN: "wasm_user/SAVE_PUSH_TOKEN",
    SAVE_PUSH_TOKEN_OK: "wasm_user/SAVE_PUSH_TOKEN_OK",
};

export const setAccount = (account) => {
    return {
        type: actions.SET_ACCOUNT,
        account: account,
    };
};

export const setLoggedIn = () => {
    return {
        type: actions.SET_LOGGED_IN,
    };
};

export const setLoggedOut = () => {
    return {
        type: actions.SET_LOGGED_OUT,
    };
};

export const updateUser = (account, cb = null) => (dispatch, getState) => {
    return Promise.resolve()
        .then(() => {
            return User.getUser();
        })
        .then((currentAccount) => {
            if(currentAccount == null)
                currentAccount = account;

            Object.keys(account).map((key) => {
                currentAccount[key] = account[key];
            });

            return User.setUser(currentAccount);
        })
        .then((account) => {
            dispatch(setAccount(account));
            
            if(typeof cb == "function")
                cb(dispatch);
        });
};

export const loginUser = (account, cb = null) => (dispatch, getState) => {
    return Promise.resolve()
        .then(() => {
            return User.setUser(account);
        })
        // Notice -> on loginFromPersistedUser pushToken should already be
        // persisted per this device!
        .then((account) => {
            return new Promise((resolve, reject) => {
                dispatch(persistPushToken(() => {
                    resolve(account);
                }));
            });
        })
        .then((account) => {
            dispatch(setAccount(account));
            dispatch(setLoggedIn());

            if(typeof cb == "function")
                cb(dispatch);
        });
};

export const logoutUser = (cb = null) => (dispatch, getState) => {
    return Promise.resolve()
        .then(() => {
            return User.rmUser();
        })
        .then(() => {
            dispatch(setLoggedOut());
            dispatch(accountDataRefetchActions.clearRefetchDataOnLogout());
            dispatch(setAccount(null));

            if(typeof cb == "function")
                cb(dispatch);
        });
};

export const setOrientation = (orientation) => {
    return {
        type: actions.SET_ORIENTATION,
        orientation: orientation,
    };
};

const setLastActivity = () => {
    return {
        type: actions.SET_LAST_ACTIVITY,
        lastActivity: moment().unix(),
    };
};

export const processAppActivity = () => (dispatch, getState) => {
    return Promise.resolve()
        .then(() => {
            dispatch(setLastActivity());
            dispatch(accountDataRefetchActions.rmInactiveRefetchDelay());
            dispatch(accountDataRefetchActions.reschedule());
        });
};

export const setPushTokenData = (pushToken = "", pushOs = "") => {
    return {
        type: actions.SET_PUSH_TOKEN_DATA,
        pushToken: pushToken,
        pushOs: pushOs,
    };
};

const savePushToken = () => {
    return {
        type: actions.SAVE_PUSH_TOKEN,
    };
};

const savePushTokenOk = () => {
    return {
        type: actions.SAVE_PUSH_TOKEN_OK,
    };
};

// Can add persistLoggedInPushToken/loggedOutPushToken
// (To persist token for logouted users with some special rules)
const persistPushToken = (onSave = () => {}) => (dispatch, getState) => {
    let state = getState();
    if(state.app.isPushTokenSaving)
        return;

    const params = {
        token: state.app.pushToken,
        os: state.app.pushOs,
    };

    dispatch(savePushToken());
    return Api.post(routes.devices, params)
        .then(json => {
            dispatch(savePushTokenOk());
            onSave(json);
        });
};

export const openPhone = (phone) => (dispatch, getState) => {
    return Promise.resolve().then(() => {
        Linking.open('tel:' + phone);
    });
};

export const openEmail = (email) => (dispatch, getState) => {
    return Promise.resolve().then(() => {
        Linking.open('mailto:' + email);
    });
};