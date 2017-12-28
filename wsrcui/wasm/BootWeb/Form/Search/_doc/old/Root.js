class Root extends Component {
    componentDidMount() {
        // @old -> Redraw after 320ms
        window.addEventListener("resize", this.redraw);

        // @old
        // Fix for correct breakpoint type detection on init in at least IE
        // setTimeout(function() {
        //     $(window).trigger(NtechApp.Autocomplete.EVENT_WINDOW_RESIZE);
        // }, 100);
    }

    getNextWidth = () => {
        // (old -> if(getCustomWidth exists) getCustomWidth(this.input));
        let nextWidth = this.props.rootWidth;
        let windowWidth = window.innerWidth;

        if(windowWidth < nextWidth)
            // @todo -> Old -> overflowPadding = 40; 
            //                 nextWidth -= 40; (Only in this case)
            nextWidth = windowWidth;
    }

    getNextHeight = () => {
        if(windowHeight < rootHeight) {
            // @todo -> Old -> overflowPadding = 40;
            //                 nextHeight -= 40; (Only in this case)
            nextHeight = windowHeight;
        }
    }
};