import React, { Component } from 'react';
import { connect } from 'react-redux';

import Str from '~/wasm/Util/Str';
import bpWidth, { breakpoints } from '~/wasm/BootWeb/Breakpoints/Store/selectors/width';

const withRenderIf = (bpWidthFn) => {
    return connect(
        (state) => {
            return {
                render: bpWidth[bpWidthFn](),
            };
        }
    )(
        class Hoc extends Component {
            render() {
                return (this.props.render) ? this.props.children : null;
            }
        }
    );
};

let exports = {};
breakpoints
    .map((value) => {
        let ucValue = Str.ucfirst(value);
        exports["IsMax" + ucValue] = withRenderIf("isMax" + ucValue);
        exports["IsMin" + ucValue] = withRenderIf("isMin" + ucValue);
        exports["IsOnly" + ucValue] = withRenderIf("isOnly" + ucValue);

        breakpoints.slice(breakpoints.indexOf(value) + 1).map((endValue) => {
            let name = "IsBetween" + ucValue + "And" + Str.ucfirst(endValue);
            let fn = withRenderIf(Str.lcfirst(name));

            exports[name] = fn;
        });
    });

export default exports;