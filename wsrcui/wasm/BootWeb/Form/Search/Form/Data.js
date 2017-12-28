import React, { Component } from 'react';

import ScrollData from './ScrollData';
import './Data.css';

// @feature -> LoadNextResults -> button or scroll(List?)
class FormData extends Component {
    constructor(props) {
        super(props);

        this.items = [];
        this.scrollData = new ScrollData(this);
    }

    componentDidUpdate() {
        this.items.filter((el) => el != null);
    }

    scrollToFocusedItem = () => {
        this.scrollData.scrollTo(
            this.items,
            this.items[this.props.focusedItemIndex],
            this.props
        );
    }

    render() {
        let dataClass = "WasmBootFormSearch__data";
        if(this.props.showDataItemsScroll)
            dataClass += " WasmBootFormSearch__data--scrollable";

        // @old -> dataItems -> css("margin", "0 auto");
        // @todo -> data -> NtechApp.lockScroll(); (Global?)
        return (
            <div 
                className="WasmBootFormSearch__dataRoot"
                ref={(el) => { this.dataRoot = el; }}
                onTouchStart={this.props.onDataRootTouchStart}
            >
                <div 
                    className={dataClass}
                    ref={(el) => { this.data = el; }}
                    onClick={this.props.onDataClick}
                >
                    <div 
                        className="WasmBootFormSearch__dataItems"
                        ref={(el) => { this.dataItems = el; }}
                    >
                        {this.props.items.map(this.renderItem)}
                    </div>
                    {this.renderSelectManyControls()}
                </div>

                <div className="WasmBootFormSearch__dataLoader">
                </div>
            </div>
        );
    }

    renderSelectManyControls = () => {
        if(!this.props.selectMany)
            return null;

        // @old -> __data -> addClass("multipleNtechAutocompleteWrapper");
        return (
            <div className="WasmBootFormSearch__selectManyControlsRoot"
                 onClick={this.onManyDoneClick}
            >
                Done
            </div>
        );
    }

    // @todo -> Highlight item text
    renderItem = (item, i) => {
        // @todo -> {this.props.renderItem(item)}
        //       -> Components -> ItemsWrap, ItemWrap, Item
        return (
            <div className="WasmBootFormSearch__dataItem"
                 key={item.id}
                 ref={(el) => { this.items[i] = el; }}
                 onClick={() => this.onItemClick(item)}
            >
                 {item.name}
            </div>
        );
    }

    onItemClick = (item) => {
        if(!this.props.isMobileFormUiOn())
            return;

        if(!this.props.selectMany) {
            this.props.onSelect(item);
            return;
        }

        this.props.onSelectMany(item);
    }

    onManyDoneClick = () => {
        this.props.onSelectManyDone();
    }
};

export default FormData;