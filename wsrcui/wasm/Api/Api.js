import Request from './Request';
import Response from './Response';
import Url from './Url';
import UserStore from '~/wasm/User/Store/User';

import * as actionsToken from '~/wasm/User/Store/actionsToken';
// @todo -> Dispatch global errors from fetch calls
import Store from '~/wasm/Store/Store';

class RestApi {
    get(url, params = {}) {
        return this.fetch(url, {method: 'GET'}, params);
    }

    delete(url, params = {}) {
        return this.fetch(url, {method: 'DELETE'}, params);
    }

    post(url, params = {}) {
        let body = JSON.stringify(params);
        return this.fetch(url, {method: 'POST', body: body});
    }

    fetch(url, extraReqParams = {}, params = {}) {
        let session = Request.initSession();
        let isGet = extraReqParams.method == "GET";
        let isDelete = extraReqParams.method == "DELETE";
        let isGetOrDelete = isGet || isDelete;

        return Request.init()
            .then(({ headers, refreshToken }) => {
                const reqParams = {
                    headers: headers,
                    ...extraReqParams,
                };

                url = Api.url + url;
                url += (isGetOrDelete) ? ("?" + Url.encodeQueryString(params)) : "";

                Request.setSession(session, reqParams, url, refreshToken);
                return fetch(url, reqParams);
            })
            .then(response => {
                return this.onFetch(response, session);
            });
    }

    onFetch(res, session) {
        const onRefreshToken = (fn) => (res) => {
            return (!session.isRefreshingToken) ? res : fn(res);
        };
        const onRefetch = (fn) => (res) => {
            return (!session.isRefetchingWithNewToken) ? res : fn(res);
        };

        return Promise.resolve(res)
            .then(response => response.json())
            .then(res => {
                Response.processErrors(res);
                
                if(Response.isAccessTokenExpired(res)) {
                    let url = Api.url + 'auth/accesstoken/byrefreshtoken';
                    let params = Request.getRefreshParams(session);

                    return fetch(url, params);
                }

                return res;
            })
            .then(onRefreshToken((res) => res.json()))
            .then(onRefreshToken((res) => {
                if(Response.isOk(res)) {
                    Request.updateAccessToken(res, session);
                    Store.getStore().dispatch(actionsToken.updateUserAccessToken(
                        res.accessToken
                    ));

                    return res;
                }

                // @todo -> Show login page??? (Refresh request failed)
            }))
            .then(onRefetch((res) => fetch(session.url, session.params)))
            .then(onRefetch((res) => res.json()))
            .then(onRefetch((res) => {
                Response.processErrors(res);
                return res;
            }));
    }
};

module.exports = new RestApi();