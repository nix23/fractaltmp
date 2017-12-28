import UserStore from './User';

export const actions = {
    SET_USER_ACCESS_TOKEN: "wasm_user/SET_USER_ACCESS_TOKEN",
};

export const setUserAccessToken = (accessToken) => {
    return {
        type: actions.SET_USER_ACCESS_TOKEN,
        accessToken: accessToken,
    };
};

export const updateUserAccessToken = (accessToken) => (dispatch, getState) => {
    return Promise.resolve()
        .then(() => {
            return UserStore.getUser();
        })
        .then((user) => {
            user.accessToken = accessToken;
            return UserStore.setUser(user);
        })
        .then(() => {
            dispatch(setUserAccessToken(accessToken));
        });
};