/*
@todo -> Test 3 use-cases(If ev is prevented)
@todo -> Decide how to use this Component
    PopupWithCanceledPullToRefresh = () =>
        <Popup ref={this.el = el}>
            <div style="position: scroll"> --> Popup Component
            </div>                         --> Pass Child el as ref!!!(ScrollArea)
        </Popup>

    or (or HOC -> create usualScrollDiv or scrollDivWithCanceledPullToRefresh)
    ScrollDivWithCanceledPullToRefresh = (Separate Component!!!)
        returns <div style="position: scroll"
                     onTouchStart="..."
                     onTouchEnd="...">Data</div>
*/
import React, { Component } from 'react';
import Viewport from '~/wasm/BootWeb/Dom/Measure/Viewport';

class PreventPullToRefresh extends Component {
    constructor(props) {
        super(props);
        
        this.isScrolling = false;
        this.disableTillEndOfCurrTouch = false;

        this.wasTouchStartedFrom = {
            topOfEl: false,
            topOfElWithOffset: false,
            bottomOfEl: false,
        };

        this.startY = null;
        this.prevY = null;
        this.maxScrollTop = null;
    }

    getScrollEl() {
        return this.scrollEl;
    }

    render() {
        return (
            <div 
                onTouchStart={this.onTouchStart}
                onTouchEnd={this.onTouchEnd}
                onTouchMove={this.onTouchMove}
                style={{overflowY: "scroll", width: "100%", height: "200px", background: "red"}}
                ref={(el) => { this.scrollEl = el; }}
            >
                <div style={{width: "100%", height: "2000px"}}>
                </div>
            </div>
        );
    }

    onTouchStart = (ev) => {
        if(this.props.disable)
            return;

        let el = this.getScrollEl();
        this.isScrolling = true;

        let y = ev.touches[0].clientY;
        this.startY = y;
        this.prevY = y;

        let visibleHeight = Viewport.getVisibleHeight(el);
        let contentHeight = el.scrollHeight;
        let maxScrollTop = contentHeight - visibleHeight;

        this.maxScrollTop = maxScrollTop;

        if(el.scrollTop == 0 && maxScrollTop == 0)
            this.wasTouchStartedFrom.topOfEl = true;
        else if(el.scrollTop <= 0) 
            this.wasTouchStartedFrom.topOfElWithOffset = true;
        else if(el.scrollTop >= maxScrollTop)
            this.wasTouchStartedFrom.bottomOfEl = true;
    }

    onTouchEnd = () => {
        this.isScrolling = false;
        this.disableTillEndOfCurrTouch = false;

        this.wasTouchStartedFrom.topOfEl = false;
        this.wasTouchStartedFrom.topOfElWithOffset = false;
        this.wasTouchStartedFrom.bottomOfEl = false;
    }

    onTouchMove = (ev) => {
        if(!this.isScrolling || this.disableTillEndOfCurrTouch)
            return;

        this.cancelEvIfTouchWasStartedFromTopOfEl(this.getScrollEl(), ev);
        this.cancelEvIfTouchWasStartedFromTopOfElWithOffset(this.getScrollEl(), ev);
        this.cancelEvIfTouchWasStartedFromBottomOfEl(this.getScrollEl(), ev);
    }

    cancelEvIfTouchWasStartedFromTopOfEl = (el, ev) => {
        if(!this.wasTouchStartedFrom.topOfEl) 
            return;

        ev.preventDefault();
    }

    cancelEvIfTouchWasStartedFromTopOfElWithOffset = (el, ev) => {
        if(!this.wasTouchStartedFrom.topOfElWithOffset)
            return;

        if(el.scrollTop > 0)
            return;

        let nextY = ev.touches[0].clientY;
        if(this.prevY < nextY)
            ev.preventDefault();
        else if(this.startY >= nextY)
            this.disableTillEndOfCurrTouch = true;

        this.prevY = nextY;
    }

    cancelEvIfTouchWasStartedFromBottomOfEl = (el, ev) => {
        if(!this.wasTouchStartedFrom.bottomOfEl)
            return;

        if(el.scrollTop < this.maxScrollTop)
            return;

        let nextY = ev.touches[0].clientY;
        if(this.prevY > nextY)
            ev.preventDefault();
        else if(this.startY <= nextY)
            this.disableTillEndOfCurrTouch = true;

        this.prevY = nextY;
    }
};

export default PreventPullToRefresh;