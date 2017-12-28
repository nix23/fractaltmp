class Str {
    trim(str) {
        return str.replace(/^\s+|\s+$/g, '');
    }

    ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    lcfirst(str) {
        return str.charAt(0).toLowerCase() + str.slice(1);
    }

    // formatTextWithMaybeDots(msg = "", maxCount = 120) {
    //     return (msg.length > maxCount) ? (msg.substr(0, maxCount)) + "..." : msg;
    // }
};

export default new Str();