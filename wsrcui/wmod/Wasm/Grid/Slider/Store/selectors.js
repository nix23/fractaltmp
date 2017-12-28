export const hasNoActiveSlide = (state) => {
    return state.info.introSlides.activeItemId == 0;
};