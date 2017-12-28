/*
    Is this still required??? (@todo -> Rm)
    (Portals are used by default now)
*/

// @todo -> Transform to WMOD???
import React, { Component } from 'react';
import { connect } from 'react-redux';

import * as formSearchActions from '~/wasm/Wasm/Boot/Web/Form/Search/Data/actions';
//import * as modalActions from '~/wasm/Wasm/Boot/Web/Modal/Data/actions';

import BrowserDetector from '~/wasm/Wasm/Boot/Web/Browser/Detector';
import Input from '../Input/Input';

import Modal from '~/wasm/Wasm/Boot/Web/Modal/Modal';
import Util from '~/wasm/Wasm/Util/Util';

import WasmBootFormSearch from '~/wasm/Wasm/Boot/Web/Form/Search/Form/Form';

import { INITIAL_INTERACTION_ID } from '~/wasm/Wasm/Boot/Web/Form/Search/Data/reducer';
const UNDEFINED_INTERACTION_ID = -1;

// @todo -> Pass input as prop(Input can be custom with icons, etc...)
// @todo -> Ensure Breakpoints are initialized before input onChange is fired!
//       -> (Restore from state? Time travel?)
class FormSearchInput extends Component {
    componentDidMount() {
        this.props.createFormSearchById(this.props.formId, this.props.items);
    }

    componentWillUnmount() {
        this.props.rmFormSearchById(this.props.formId);
    }

    componentWillReceiveProps(nextProps) {
        if(nextProps.inputInteractionId == UNDEFINED_INTERACTION_ID ||
           nextProps.inputInteractionId == INITIAL_INTERACTION_ID)
            return;
        if(nextProps.inputInteractionId == this.props.inputInteractionId)
            return;

        let isLastSearch = (state, valueOnSearchFetchStart) => {
            let value = state.formSearch.byFormId[
                this.props.formId
            ].searchValue;
            return value != valueOnSearchFetchStart;
        };
        let toggleLoading = () => {
            return {
                on: () => this.props.loadingOn(this.props.formId),
                off: () => this.props.loadingOff(this.props.formId),
            };
        };

        this.props.onSearch(nextProps.searchValue, isLastSearch, toggleLoading);
    }

    render() {
        return (
            <div>
                <Input
                    {...this.props}
                    inputWithMobileOverlay
                />
                <Modal>
                    <WasmBootFormSearch
                        {...this.props}
                    />
                </Modal>
            </div>
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
            inputInteractionId: (searchData !== null)
                ? searchData.inputInteractionId
                : UNDEFINED_INTERACTION_ID,
            searchValue: (searchData !== null) ? searchData.searchValue : "",
        };
    },
    (dispatch) => {
        return {
            createFormSearchById: (formId, items) => {
                dispatch(formSearchActions.createFormSearchDataById(
                    formId
                ));
                dispatch(modalActions.pushModalComponent(
                    "WASM_BOOT_FORM_SEARCH", 
                    {
                        formId: formId,
                        isOpened: false,
                        items: items,
                    }
                ));
            },
            rmFormSearchById: (formId) => {
                dispatch(formSearchActions.rmFormSearchDataById(
                    formId
                ));
                dispatch(modalActions.rmModalComponent(
                    "WASM_BOOT_FORM_SEARCH"
                ));
            },
            loadingOn: (formId) => {
                dispatch(formSearchActions.loadingOn(formId));
            },
            loadingOff: (formId) => {
                dispatch(formSearchActions.loadingOff(formId));
            },
        };
    }
)(FormSearchInput);