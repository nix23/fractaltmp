/*
    Is this still required??? (@todo -> Rm)
    (Portals are used by default now)
*/

import React, { Component } from 'react';
import { connect } from 'react-redux';

import * as formSearchActions from '~/wasm/Wasm/Boot/Web/Form/Search/Data/actions';

import Arr from '~/wasm/Util/Arr';
import Breakpoints from '../Breakpoints/Breakpoints';
import MobileControls from './Controls';
import FormData from './Data';
import Root from './Root';
import Util from '~/wasm/Wasm/Util/Util';
import './Form.css';

/*
    <HighlightMatch> -> Component(Allow Override) or just Match
                     -> matchType = START||END||FULL (FULL by def)
    clearOnSelect = true || false

    <Loader> -> By default empty. 
             -> Write Fractal loader Connector and connect to def Fractal
                loader(Default just text with dots???)

    <NoResults> -> By def icon + "No results found." if(val == 0) -> "Search"
                -> Pass SearchNoResults comp. with searchValue to List
*/
const SHOW_MOBILE_KEYBOARD_DELAY = 320;

// @todo -> Add Connectors??? 
//      -> Fractal Connector
//      -> Default React connector(As separate Lib)
//      -> Redux Connector (...etc)
class FormSearch extends Component {
    constructor(props) {
        super(props);

        this.openMobileKeyboardTimeout = null;
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
        // @todo -> this.input.removeClasss("selectedInput");
        if(this.Breakpoints.isMobile() || this.Root.root.contains(ev.target))
            return;

        // old -> if(multiple) return;
        this.off(); // @todo
    }

    componentWillReceiveProps(nextProps) {
        if(nextProps.inputInteractionId == this.props.inputInteractionId)
            return;
        if(nextProps.items !== this.props.items)
            this.Root.redraw();

        this.openFormSearchOnInputInteraction(nextProps.searchValue);
    }

    openFormSearchOnInputInteraction = (nextValue) => {
        if(this.Breakpoints.isDesktop()) {
            this.openFormSearchOnDesktop(nextValue);
        }

        if(this.Breakpoints.isMobile()) {
            this.openFormSearchOnMobile(nextValue);
        }
    }

    openFormSearchOnDesktop = (nextValue) => {
        if(nextValue.length >= this.getMinCharsToOpenForm())
            this.on(); // @todo
        else
            this.off(); // @todo
    }

    openFormSearchOnMobile = (nextValue) => {
        if(!this.isOn() && !BrowserDetector.isIos()) { // @todo -> Implement
            // Ios allows to focus only inside onclick event
            // (This case is used for all other platforms)
            this.openMobileKeyboardTimeout = setTimeout(() => {
                // this.inputClone.focus();
            }, SHOW_MOBILE_KEYBOARD_DELAY);
        }

        this.on(); // @todo
    }

    on = () => {
        // @todo -> Enable loading
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
        return this.Breakpoints.isDesktop() || this.state.isMobileFormUiOn;
    }

    blurInputCloneOnMobileForm = () => {
        if(!this.isMobileFormUiOn())
            return;

        this.MobileControls.blurInputClone();
    }

    focusInputCloneOnGhostClickForIos = () => {
        if(!this.isMobileFormUiOn())
            return;

        this.MobileControls.focusInputClone();
    }

    onSelect = (item) => {
        if(!this.props.selectMany) {
            this.props.onSelect(item);
            this.props.clearSearchValue(this.props.formId);

            this.off(); // @todo
            return;
        }
    }

    onSelectMany = (item) => {
        this.setState({selectedItems: Arr.toggle(
            this.state.selectedItems, item
        )});
    }

    onSelectManyDone = () => {
        this.off(); // @todo
        this.props.clearSearchValue(this.props.formId);
    }

    render() {
        // Old: if(this.wrapperClass != null) addClassToRoot
        return (
            <Root
                getFormData={() => this.FormData}
                {...this.state}
                onRootRedraw={this.onRootRedraw}
                setIsMobileFormUiOn={this.setIsMobileFormUiOn}
                ref={(C) => { this.Root = C; }}
            >
                <Breakpoints
                    ref={(C) => { this.Breakpoints = C; }}
                    onChangeBreakpointValue={() => {}}
                />
                <MobileControls
                    ref={(C) => { this.MobileControls = C; }}
                    isMobileFormUiOn={this.isMobileFormUiOn}
                />
                <FormData
                    ref={(C) => { this.FormData = C; }}
                    {...this.state}
                    {...this.props}
                    onDataRootTouchStart={this.blurInputCloneOnMobileForm}
                    onDataClick={this.focusInputCloneOnGhostClickForIos}
                    isMobileFormUiOn={this.isMobileFormUiOn}
                    onSelect={this.onSelect}
                    onSelectMany={this.onSelectMany}
                    onSelectManyDone={this.onSelectManyDone}
                />
            </Root>
        );
    }
};

export default connect(
    (state, props) => {
        let searchData = Util.getIfDefined(
            state.formSearch.byFormId,
            props.formId,
            null
        );

        return {
            focusedItemIndex: searchData.focusedItemIndex,
            wasFocusedWithKeyUp: searchData.wasFocusedWithKeyUp,
            isOpened: props.isOpened,
            searchValue: searchData.searchValue,
            inputInteractionId: searchData.inputInteractionId,
            isLoading: searchData.isLoading,
        };
    },
    (dispatch) => {
        return {
            clearSearchValue: (formId) => {
                dispatch(formSearchActions.setFormSearchValue(formId, ""));
            },
        };
    }
)(FormSearch);