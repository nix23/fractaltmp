import React, { Component } from 'react';
import { connect } from 'react-redux';

import ModalScene from '~/wasm/BootWeb/Modal/Scene/SceneWithOverlay';

class DebugView extends Component {
    constructor(props) {
        super(props);
        this.state = {
            render: false,
        };
    }

    toggleRender = () => {
        this.setState({render: !this.state.render});
    }

    render() {
        return (
            <div>
                <ModalScene
                    onOverlayClick={this.toggleRender}
                    render={this.state.render}
                />
                <div onClick={this.toggleRender}
                     style={{marginTop: "50px"}}>
                    Toggle 
                </div>
            </div>
        );
    }
}

export default connect(
    (state) => {
        return {
        };
    },
    (dispatch) => {
        return {
        };
    }
)(DebugView);