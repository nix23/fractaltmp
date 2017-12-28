// @deprecated
// @todo -> Rm? + (Rm Data act.-reducers)
//      -> Create Deprecated(Old) folder for such stuff?
import React, { Component } from 'react';
import { connect } from 'react-redux';

import WasmBootFormSearch from '~/wasm/BootWeb/Form/Search/Form/Form';

// @todo -> Transform to WMOD(Control priorities, etc from WasmCmf???)
// @todo -> Add render priority??? (FormSearch > ModalForm, Confirm > ModalForm, etc)
//       -> Sort by priority
// @todo -> Import dynamically(Components should not live here)
//       -> Import special file which exports Components map with id-Comp???
//  -> Or we can implement auto-priorities with Portals?
//      -> (just render sequentially)
const Components = {
    WASM_BOOT_FORM_SEARCH: WasmBootFormSearch,
};

class Modal extends Component {
    render() {
        return (
            <div>
                {this.props.modals.map((modal, i) => {
                    return this.renderModal(modal, i);
                })}
            </div>
        );
    }

    renderModal = (modal, key) => {
        let Component = Components[modal.id];
        return (
            <Component key={key} modalId={modal.id} {...modal.props}/>
        );
    }
};

export default connect(
    (state) => {
        return {
            modals: state.modal.modals,
        };
    },
    (dispatch) => {
        return {
        };
    }
)(Modal);