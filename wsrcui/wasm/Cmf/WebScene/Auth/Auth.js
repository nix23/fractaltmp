import React, { Component } from 'react';
import { connect } from 'react-redux';

import './Auth.css';

class Scene extends Component {
    render() {
        return (
            <div>
                Auth
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
)(Scene);