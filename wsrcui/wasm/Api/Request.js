import UserStore from '~/wasm/User/Store/User';

const headers = {
    'Accept': 'application/hal+json,application/problem+json',
    'Content-Type': 'application/json',
};

class Request {
    setAccessTokenHeader(headers, token) {
        headers['Authorization'] = 'Bearer ' + token;
    }

    init() {
        return UserStore.getUser().then(user => {
            let requestHeaders = {...headers};
            let refreshToken = null;

            if(user !== null) {
                refreshToken = user.refreshToken;
                this.setAccessTokenHeader(
                    requestHeaders, 
                    user.accessToken
                );
            }

            return {
                headers: requestHeaders,
                refreshToken: refreshToken,
            };
        });
    }

    initSession() {
        let session = {
            params: null,
            url: null,
            refreshToken: null,
            isRefreshingToken: false,
            isRefetchingWithNewToken: false,
        };

        return session;
    }

    setSession(session, params, url, refreshToken) {
        session.params = params;
        session.url = url; 
        session.refreshToken = refreshToken;
    }

    getRefreshParams(session) {
        session.isRefreshingToken = true;

        // If refreshToken == null -> Redirect to login page
        // (If request with required AUTH)

        const params = {
            method: 'POST',
            headers: {...headers},
            body: JSON.stringify({refreshToken: session.refreshToken}),
        };

        return params;
    }

    updateAccessToken(res, session) {
        session.isRefetchingWithNewToken = true;
        this.setAccessTokenHeader(
            session.params.headers,
            res.accessToken 
        );
    }
}

module.exports = new Request();