const KEY_UP = 38;
const KEY_DOWN = 40;
const KEY_LEFT = 37;
const KEY_RIGHT = 39;
const KEY_ENTER = 13;

const HOLD_INITIAL_DELAY = 300;
const HOLD_DELAY = 70;

class KeyEvents {
    constructor(Input) {
        this.Input = Input;
        this.holdTimeouts = [];
        this.holdedArrowKey = null;
        this.wasEnterPressed = false;
    }

    isHoldingArrowKey() {
        return this.holdedArrowKey != null;
    }

    unsetHoldedArrowKey() {
        this.holdedArrowKey = null;
    }

    onArrowHold(props) {
        this.onArrowPress(keyCode, props);
        this.holdTimeouts.push(setTimeout(() => {
            this.onArrowHold(props);
        }, HOLD_DELAY));
    }

    onArrowPress(keyCode, props) {
        let isKeyUp = keyCode == KEY_UP;
        let isKeyDown = keyCode == KEY_DOWN;

        let prevFocusedItemIndex = null;
        let nextFocusedItemIndex = null;

        if(props.focusedItemIndex == null) {
            nextFocusedItemIndex = (isKeyUp) ? props.items.length - 1 : 0;
            this.onFocusNext(nextFocusedItemIndex, props);
            return;
        }

        prevFocusedItemIndex = (props.focusedItemIndex - 1 >= 0)
            ? props.focusedItemIndex - 1 : null;
        nextFocusedItemIndex = (props.focusedItemIndex + 1 <= props.items.length - 1)
            ? props.focusedItemIndex + 1 : null;

        if(isKeyUp) {
            if(prevFocusedItemIndex != null) {
                this.onFocusNext(prevFocusedItemIndex, props, isKeyUp);
                return;
            }
        }
        else if(isKeyDown) {
            if(nextFocusedItemIndex != null) {
                this.onFocusNext(nextFocusedItemIndex, props, isKeyUp);
                return;
            }
        }

        this.onFocusInputOnArrowPress(props);
    }

    onFocusNext(nextFocusedItemIndex, props, isKeyUp = false) {
        props.setFocusedItemIndexWithKeyUp(
            nextFocusedItemIndex,
            isKeyUp,
            () => props.scrollToFocusedItem()
        );
    }

    onFocusInputOnArrowPress(props) {
        props.setFocusedItemIndex(null);
        this.Input.preventFetchDataOnNextFocus = true;
        this.Input.focus();
    }

    onArrowUnfocus(props) {
        props.unfocusItem();
    }

    onKeyDown(event, props) {
        if(event.target.keyCode != KEY_UP && 
           event.target.keyCode != KEY_DOWN)
            return;
        if(this.isHoldingArrowKey())
            return;

        this.holdedArrowKey = event.target.keyCode;
        this.holdTimeouts.push(setTimeout(() => {
            if(!this.isHoldingArrowKey())
                return;

            this.onArrowHold(props);
        }, HOLD_INITIAL_DELAY));
    }

    onKeyUp(event, props) {
        let nextValue = event.target.value;
        this.unsetHoldedArrowKey();

        this.holdTimeouts.map((tm) => clearTimeout(tm));
        this.holdTimeouts = [];

        if(event.target.keyCode == KEY_ENTER) {
            this.wasEnterPressed = true;
            if(props.isLoading)
                return;

            this.onEnterPress(nextValue, props);
            return;
        }

        if(event.target.keyCode == KEY_UP || 
           event.target.keyCode == KEY_DOWN) {
            this.onArrowPress(event.target.keyCode, props);
            return;
        }

        if(event.target.keyCode == KEY_LEFT ||
           event.target.keyCode == KEY_RIGHT) {
            return;
        }

        this.onArrowUnfocus(props);
    }

    onEnterPress(nextValue, props) {
        if(!this.wasEnterPressed)
            return;

        this.wasEnterPressed = false;
        if(props.focusedItemIndex != null) {
            props.onSelect(props.focusedItemIndex);
            return;
        }

        // @feature, @old -> Check all items if any val == nextValue 
        //                -> (try autoselect item with input val)
        //                   (match) ? props.onSelect(itemValues) 
        //                   showInfo("result not found.");
    }
};

export default KeyEvents;