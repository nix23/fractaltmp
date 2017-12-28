import React, { Component } from 'react';

import Arr from '~/wasm/Util/Arr';
import MobileControls from './Controls';
import FormData from './Data';
import Root from './Root';
import Util from '~/wasm/Util/Util';

// @feature -> <Loader> = Def Fractal loader(Text with dots?)
// @feature -> <NoResults> = if(val == 0) -> "Enter v" : "Icon + No res f"
export default class FormSearch extends Component {
    constructor(props) {
        super(props);

        this.state = {
            rootWidth: 0,
            rootHeight: 0,
            rootLeft: 0,
            rootTop: 0,
            showDataItemsScroll: false,
            isMobileFormUiOn: false,
            selectedItems: [],
            isOn: false,
        };
    }

    onRootRedraw = (nextState) => {
        this.setState(nextState);
    }

    componentDidMount() {
        document.addEventListener('mousedown', this.onBodyClick);
    }

    componentWillUnmount() {
        document.removeEventListener('mousedown', this.onBodyClick);
    }

    onBodyClick = (ev) => {
        this.MobileControls.setUnfocusedInputState();
        this.props.setUnfocusedInputState();
        if(this.props.isMobile || 
           this.Root.getRootDomEl().contains(ev.target))
            return;

        // @old -> if(multiple) return;
        this.off();
    }

    componentWillReceiveProps(nextProps) {
        if(nextProps.items !== this.props.items)
            this.Root.redraw();

        if(this.state.isOn)
            return;

        this.openFormSearchOnInputInteraction(
            nextProps.isDesktop,
            nextProps.isMobile,
            nextProps.searchValue
        );
    }

    openFormSearchOnInputInteraction = (isDesktop, isMobile, nextValue) => {
        if(isDesktop)
            this.openFormSearchOnDesktop(nextValue);

        if(isMobile)
            this.openFormSearchOnMobile(nextValue);
    }

    openFormSearchOnDesktop = (nextValue) => {
        if(nextValue.length >= this.getMinCharsToOpenForm())
            this.on();
        else
            this.off();
    }

    openFormSearchOnMobile = (nextValue) => {
        this.Root.getRoot().scheduleOpenKeyboard();
        this.on();
    }

    on = () => {
        this.props.loadingOn();
        this.setState({isOn: true});
    }

    off = () => {
        this.setState({isOn: false});
    }

    isOn = () => {
        return this.state.isOn;
    }

    // Will affect only Desktop
    getMinCharsToOpenForm = () => {
        return Util.getIfDefined(this.props, "minCharsToOpenForm", 1);
    }

    setIsMobileFormUiOn = (nextValue = true) => {
        this.setState({isMobileFormUiOn: nextValue});
    }

    isMobileFormUiOn = () => {
        return this.props.isDesktop || this.state.isMobileFormUiOn;
    }

    blurMobileInputOnMobileForm = () => {
        if(!this.isMobileFormUiOn())
            return;

        this.MobileControls.blurMobileInput();
    }

    focusMobileInputOnGhostClickForIos = () => {
        if(!this.isMobileFormUiOn())
            return;

        this.MobileControls.focusMobileInput();
    }

    focusMobileInput = () => {
        this.MobileControls.focusMobileInput();
    }

    blurMobileInput = () => {
        this.MobileControls.blurMobileInput();
    }

    onSelect = (item) => {
        if(!this.props.selectMany) {
            this.props.onSelect(item);
            this.clearSearchValue();

            this.off();
            return;
        }
    }

    onSelectMany = (item) => {
        this.setState({selectedItems: Arr.toggle(
            this.state.selectedItems, item
        )});
    }

    onSelectManyDone = () => {
        this.off();
        this.clearSearchValue();
    }

    clearSearchValue = () => {
        this.props.setSearchValue("");
    }

    scrollToFocusedItem = () => {
        this.FormData.scrollToFocusedItem();
    }

    render() {
        return (
            <Root
                getFormData={() => this.FormData}
                {...this.state}
                onRootRedraw={this.onRootRedraw}
                setIsMobileFormUiOn={this.setIsMobileFormUiOn}
                blurMobileInput={this.blurMobileInput}
                getNextInputWidth={this.props.getNextInputWidth}
                ref={(C) => { this.Root = C; }}
            >
                <MobileControls
                    ref={(C) => { this.MobileControls = C; }}
                    isMobileFormUiOn={this.isMobileFormUiOn}
                    closeForm={this.off}
                    searchValue={this.props.searchValue}
                />
                <FormData
                    ref={(C) => { this.FormData = C; }}
                    {...this.state}
                    {...this.props}
                    onDataRootTouchStart={this.blurMobileInputOnMobileForm}
                    onDataClick={this.focusMobileInputOnGhostClickForIos}
                    isMobileFormUiOn={this.isMobileFormUiOn}
                    onSelect={this.onSelect}
                    onSelectMany={this.onSelectMany}
                    onSelectManyDone={this.onSelectManyDone}
                />
            </Root>
        );
    }
};