const getOrientationBreakpoint = () => (dispatch, getState) => {
    return Promise.resolve().then(() => getState().app.breakpoints.orientation);
};

export const isLandscape = () => (dispatch, getState) => {
    return getOrientationBreakpoint().then((value) => value == "landscape");
};

export const isPortrait = () => (dispatch, getState) => {
    return getOrientationBreakpoint().then((value) => value == "portrait");
};