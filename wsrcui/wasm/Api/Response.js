class Response {
    processErrors(res) {
        if(typeof res.error !== "undefined" &&
           typeof res.error.code !== "undefined" &&
           res.error.code == 500)
            cl(res);
    }

    isAccessTokenExpired(res) {
        return (typeof res.status !== "undefined" && res.status == 401);
    }

    isOk(res) {
        return (typeof res.status !== "undefined" && res.status == 200);
    }
}

module.exports = new Response();