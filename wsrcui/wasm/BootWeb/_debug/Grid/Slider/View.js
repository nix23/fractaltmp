import React, { Component } from 'react';
import { connect } from 'react-redux';

import GridSlider from '~/wasm/BootWeb/Grid/Slider/Slider';
import './Slide.css';

class DebugView extends Component {
    constructor(props) {
        super(props);
        this.state = {
            items: [
                {
                    id: 1,
                    url: 'https://fabalist.com/uploads/1/8/790_1_8_0_557-1-1-0-3.png',
                    name: 'Item1',
                    description: 'Item1',
                },
                {
                    id: 2,
                    url: 'https://fabalist.com/uploads/1/8/790_1_8_0_557-1-1-0-3.png',
                    name: 'Item2',
                    description: 'Item2',
                },
                {
                    id: 3,
                    url: 'https://fabalist.com/uploads/1/8/790_1_8_0_557-1-1-0-3.png',
                    name: 'Item3',
                    description: 'Item3',
                },
            ],
        };
    }

    render() {
        return (
            <GridSlider
                items={this.state.items}
                renderItem={this.renderItem}
            />
        );
    }

    renderItem = (item) => {
        return (
            <div>
                <div className="slideHeightSetter"/>
                <img className="slideImage" src={item.url}/>
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