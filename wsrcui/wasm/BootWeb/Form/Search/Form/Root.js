import React, { Component } from 'react';

import RootDomEl from './RootDomEl';
import MeasureSizes from '~/wasm/BootWeb/Dom/Measure/Sizes';
import Viewport from '~/wasm/BootWeb/Dom/Measure/Viewport';
import Util from '~/wasm/Util/Util';

const FORM_SEARCH_MAX_HEIGHT = 500;

class Root extends Component {
    componentDidMount() {
        this.redraw();
        window.addEventListener("resize", this.redraw);
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.redraw);
    }

    redraw = () => {
        let { nextHeight, showDataItemsScroll } = this.getNextHeight();

        let nextState = {
            rootWidth: this.getNextWidth(),
            rootHeight: nextHeight,
            rootLeft: this.getNextLeft(),
            rootTop: this.getNextTop(),
            showDataItemsScroll: showDataItemsScroll,
        };

        let hasNewState = false;
        for(var prop in nextState) {
            if(nextState[prop] != this.props[prop]) 
                hasNewState = true;
        }

        if(!hasNewState)
            return;

        this.props.onRootRedraw(nextState);
    }

    getNextWidth = () => {
        // @feature -> use formSearchMinWidth instead of getCustomWidth
        let nextWidth = this.props.getNextInputWidth();
        let windowWidth = window.innerWidth;

        if(windowWidth < nextWidth)
            nextWidth = windowWidth;

        return nextWidth;
    }

    getNextHeight = () => {
        let rootHeight = MeasureSizes.outerHeight(this.rootDomEl);
        let rootMaxHeight = this.getMaxHeight();

        let dataItemsHeight = MeasureSizes.outerHeight(
            this.props.getFormData().dataItems
        );
        let windowHeight = window.innerHeight;

        let nextHeight = rootHeight;
        let showDataItemsScroll = false;

        if(windowHeight < rootHeight) {
            nextHeight = windowHeight;
            if(dataItemsHeight > nextHeight)
                showDataItemsScroll = true;
        }
        else {
            if(dataItemsHeight > rootMaxHeight) {
                nextHeight = rootMaxHeight;
                showDataItemsScroll = true;
            }
            else
                nextHeight = dataItemsHeight;
        }

        return {
            nextHeight: nextHeight,
            showDataItemsScroll: showDataItemsScroll,
        };
    }

    getMaxHeight = () => {
        return Util.getIfDefined(
            this.props, "formSearchMaxHeight", FORM_SEARCH_MAX_HEIGHT
        );
    }

    getNextLeft = () => {
        let windowWidth = window.innerWidth;
        let rootWidth = this.props.rootWidth;

        let inputWidth = this.props.searchInputWidth;
        let inputOffsetLeft = this.props.searchInputOffsetLeft;

        let inputCenterX = inputOffsetLeft + inputWidth - (inputWidth / 2);
        let nextLeft = inputCenterX - (rootWidth / 2);

        if(nextLeft + rootWidth > windowWidth)
            nextLeft = windowWidth - rootWidth;
        if(nextLeft <= 0)
            nextLeft = 0;

        return nextLeft;
    }

    getNextTop = () => {
        let windowY2 = Viewport.scrollTop() + window.innerHeight;
        let rootHeight = this.props.rootHeight;

        let inputHeight = this.props.searchInputHeight;
        let inputOffsetTop = this.props.searchInputOffsetTop;

        let nextTop = inputOffsetTop + inputHeight + 1;

        if(nextTop + rootHeight > windowY2)
            nextTop = windowY2 - rootHeight;
        if(nextTop <= 0)
            nextTop = 0;

        return nextTop;
    }

    getRoot = () => {
        return this.RootDomEl;
    }

    getRootDomEl = () => {
        return this.rootDomEl;
    }

    onRootDomElRef = (C) => {
        if(C == null)
            return;

        this.RootDomEl = C;
        this.rootDomEl = C.getRoot();
    }

    render() {
        return (
            <RootDomEl
                {...this.props}
                ref={this.onRootDomElRef}
            >
                {this.props.children}
            </RootDomEl>
        );
    }
};

export default Root;