// @todo -> Implement
class Scroll {
    scrollTo($item, cb, delay) {
        var cb = cb || () => { ; };
        var delay = delay || 350;

        $("body").animate({ scrollTop: $item.offset().top }, delay);
        $("html").animate({ scrollTop: $item.offset().top }, delay, cb);
    }

    scrollToPx(px, cb, delay) {
        var cb = cb || () => { ; };
        var delay = delay || 350;

        $("body").animate({ scrollTop: px }, delay);
        $("html").animate({ scrollTop: px }, delay, cb);
    }

    scrollItemTo($item, px, cb, delay) {
        var cb = cb || function() { ; };
        var delay = delay || 350;

        $item.animate({ scrollTop: px }, delay);
    }

    lockParentMousewheelScroll($item) {
        var handler = (e) => {
            var event = e.originalEvent,
                    d = event.wheelDelta || -event.detail;

            this.scrollTop += ( d < 0 ? 1 : -1 ) * 30;
            e.preventDefault();
        };
        $item.on('mousewheel.NtechApp.lockParentMousewheelScroll', handler);
//                var enableLock = enableLock || function() { return true; };
//
//                var handler = function(e) {
//                    var event = e.originalEvent,
//                            d = event.wheelDelta || -event.detail;
//
//                    this.scrollTop += ( d < 0 ? 1 : -1 ) * 30;
//                    e.preventDefault();
//                };
//                var bindLockHandler = function() { console.log("lock");
//                    $item.on('mousewheel.NtechApp.lockParentMousewheelScroll', handler);
//                    isEnabled = true;
//                };
//                var unbindLockHandler = function() { console.log("unlock");
//                    $item.off('mousewheel.NtechApp.lockParentMousewheelScroll', handler);
//                    isEnabled = false;
//                };
//                var isEnabled = false;
//
//                if(enableLock())
//                    bindLockHandler();
//
//                $(window).on('resize.NtechApp.lockParentMousewheelScroll', function() {
//                    if(enableLock()) {
//                        if(isEnabled) return;
//                        bindLockHandler();
//                    }
//                    else {
//                        if(!isEnabled) return;
//                        unbindLockHandler();
//                    }
//                });
    }

    lockScroll($item, enableLock) {
        NtechApp.lockParentMousewheelScroll($item);
        var locker = new NtechApp.ReloadOnScrollLocker($item);

        return locker;
    }

    lockMobileScroll($item) {
        var locker = new NtechApp.ReloadOnScrollLocker($item);
        return locker;
    }
};

export default new Scroll();
