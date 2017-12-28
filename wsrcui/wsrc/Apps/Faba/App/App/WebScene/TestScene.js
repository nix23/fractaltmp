import React from 'react';
import RenderIf from '~/wasm/BootWeb/Breakpoints/RenderIf';

import 1Col2Rows from '~/wasm/LayoutWeb/BySize/Grid/1Col2Rows';

// @todo -> Pass RenderIf (Is...) breakpoint type to childs?
//       -> +Grid type? (Two-cols, One-col?)
// (Can check inside ModuleInstance)
// Vs item-width MediaQuery

// TabsMobile, MultiCols BigScreen
// Ith Scene = Grid || Tabs (GridTwoColsWithHeader, GridThreeSwapableCols)
// Ith Module = N Presets Of Layout(Views), 1 Container(AppModule/WM+Store)
// Restaurants + Services can be rendered with same Preset of Layout(View)
// Components are constructed from blocks('atoms') -> decoupled + can switch
// Each ModInstance can be rerendered with Nth Component
export default class Scene extends React.Component {
    render() {
        return (
            <div>
                <RenderIf.IsMinXs>
                    <1Col2Rows/>
                </RenderIf>
            </div>
        );
    }
}