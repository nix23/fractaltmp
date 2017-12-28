import React, { Component } from 'react';

class Nav extends Component {
    render() {
        return (
            <div className="WasmBootGridSlider__nav">
                {this.props.items.map(this.renderItem)}
            </div>
        );
    }

    renderItem = (item, i) => {
        let activeClass = "WasmBootGridSlider__navItem";
        if(this.props.activeItem == i)
            activeClass += " WasmBootGridSlider__navItem--active";

        return (
            <div className={activeClass}
                 key={item.id}>
            </div>
        );
    }
};

export default Nav;