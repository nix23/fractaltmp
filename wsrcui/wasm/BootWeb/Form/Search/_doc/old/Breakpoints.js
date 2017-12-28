class Breakpoints extends Component {
    componentDidMount() {
        this.resolveInitialBreakpoints();
    }

    // @old -> Fix for IE and maybe other browsers.
    // @old -> Was called onConstruct && onResize
    afterResolveNextBreakpoints() {
        if(this.props.breakpointValue == "mobile" ||
           this.props.breakpointValue == "desktop")
           return;

        let windowWidth = window.innerWidth;
        let value = "desktop";

        if(windowWidth <= 999)
            value = "mobile";

        this.props.setBreakpoint(value);
    }
};