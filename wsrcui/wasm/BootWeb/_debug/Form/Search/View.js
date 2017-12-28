import React, { Component } from 'react';
import { connect } from 'react-redux';

import GridSlider from '~/wasm/BootWeb/Grid/Slider/Slider';

import FormSearchInput from '~/wasm/BootWeb/Form/Search/SearchInput/Input';
import * as dataFormSearchDemoActions from '~/wasm/BootWeb/_debug/Form/Search/Store/actions';

class DebugView extends Component {
    constructor(props) {
        super(props);
        this.state = {

        };
    }

    render() {
        return (
            <FormSearchInput
                onSearch={this.props.onFormSearch}
                items={this.props.formSearchItems}
            />
        );
    }
}

export default connect(
    (state) => {
        return {
            formSearchItems: state.formSearchDemo.items,
        };
    },
    (dispatch) => {
        return {
            onFormSearch: (searchValue, isLastSearch, loading) => {
                dispatch(dataFormSearchDemoActions.fetchSearch(
                    searchValue, isLastSearch, loading
                ));
            },
        };
    }
)(DebugView);