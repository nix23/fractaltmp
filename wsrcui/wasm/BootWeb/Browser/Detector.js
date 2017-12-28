import Bowser from 'bowser';

// @todo -> Implement
let browser = null;
class Detector {
    initialize() {
        browser = (typeof navigator != "undefined") ? navigator.userAgent : '';
    }

    isAndroid() {
        return /android/i.test(browser);
    }

    isAndroidFirefox() {
        if(!this.isAndroid())
            return false;

        return /firefox|iceweasel/i.test(browser);
    }

    isAndroidUcBrowser() {
        if(!this.isAndroid())
            return false;

        return /UCBrowser/i.test(browser);
    }

    isIos() {
        return typeof Bowser.ios !== "undefined";
    }
};

export default new Detector();