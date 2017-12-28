// @todo -> Transform to WMOD???
import React, { Component } from 'react';

import Breakpoints from '../Breakpoints/Breakpoints';
import BrowserDetector from '~/wasm/BootWeb/Browser/Detector';
import Input from '../Input/Input';

import Modal from '~/wasm/BootWeb/Modal/Modal';
import Util from '~/wasm/Util/Util';

import WasmBootFormSearch from '~/wasm/BootWeb/Form/Search/Form/Form';

// @feature -> Input(Row) props || C
export default class FormSearchInput extends Component {
    constructor(props) {
        super(props);

        this.state = {
            breakpointValue: "",
            searchValue: "",
            isLoading: false,
            isModalFormOpened: false,
            focusedItemIndex: null,
            wasFocusedWithKeyUp: false,
        };
    }

    onChangeBreakpointValue = (nextValue) => {
        this.setState({breakpointValue: nextValue});
    }

    execSearch = () => {
        let isLastSearch = (valueOnFetchStart) => {
            return this.state.searchValue != valueOnFetchStart;
        };
        let toggleLoading = () => {
            return {
                on: () => this.loadingOn(),
                off: () => this.loadingOff(),
            };
        };

        this.props.onSearch(
            this.state.searchValue, isLastSearch, toggleLoading
        );
    }

    onSelect = (item) => {
        console.log("********* ITEM SELECTED: ");
        console.log(item);
    }

    loadingOn = () => {
        this.setState({isLoading: true});
    }

    loadingOff = () => {
        this.setState({isLoading: false});
    }

    setSearchValue = (nextValue, onSet = () => {}) => {
        this.setState({searchValue: nextValue}, onSet);
    }

    openModalForm = () => {
        this.setState({isModalFormOpened: true});
    }

    closeModalForm = () => {
        this.setState({isModalFormOpened: false});
    }

    unfocusItem = () => {
        this.setState({focusedItemIndex: null});
    }

    focusMobileInput = () => {
        this.FormSearch.focusMobileInput();
    }

    blurMobileInput = () => {
        this.FormSearch.blurMobileInput();
    }

    setFocusedItemIndex = (index) => {
        this.setState({focusedItemIndex: index});
    }

    setFocusedItemIndexWithKeyUp = (index, isKeyUp, onSet = () => {}) => {
        this.setState({
            focusedItemIndex: index,
            wasFocusedWithKeyUp: isKeyUp,
        }, onSet);
    }

    scrollToFocusedItem = () => {
        this.FormSearch.scrollToFocusedItem();
    }

    setUnfocusedInputState = () => {
        this.Input.setUnfocusedInputState();
    }

    getNextInputWidth = () => {
        return this.Input.getInputWidth();
    }

    render() {
        let extraProps = {
            execSearch: this.execSearch,
            onSelect: this.onSelect,
            loadingOn: this.loadingOn,
            loadingOff: this.loadingOff,
            setSearchValue: this.setSearchValue,
            openModalForm: this.openModalForm,
            closeModalForm: this.closeModalForm,
            unfocusItem: this.unfocusItem,
            focusMobileInput: this.focusMobileInput,
            blurMobileInput: this.blurMobileInput,
            setFocusedItemIndex: this.setFocusedItemIndex,
            setFocusedItemIndexWithKeyUp: this.setFocusedItemIndexWithKeyUp,
            scrollToFocusedItem: this.scrollToFocusedItem,
            setUnfocusedInputState: this.setUnfocusedInputState,
            getNextInputWidth: this.getNextInputWidth,
            isMobile: (this.Breakpoints) 
                ? this.Breakpoints.isMobile(this.state.breakpointValue) 
                : false,
            isDesktop: (this.Breakpoints) 
                ? this.Breakpoints.isDesktop(this.state.breakpointValue) 
                : false,
        };
        
        return (
            <div>
                <Breakpoints
                    ref={(C) => { this.Breakpoints = C; }}
                    breakpointValue={this.state.breakpointValue}
                    onChangeBreakpointValue={this.onChangeBreakpointValue}
                />
                <Input
                    {...this.props}
                    {...this.state}
                    {...extraProps}
                    inputWithMobileOverlay
                    ref={(C) => { this.Input = C; }}
                />
                <Modal>
                    <WasmBootFormSearch
                        {...this.props}
                        {...this.state}
                        {...extraProps}
                        unfocusItem={this.unfocusItem}
                        ref={(C) => { this.FormSearch = C; }}
                    />
                </Modal>
            </div>
        );
    }
};