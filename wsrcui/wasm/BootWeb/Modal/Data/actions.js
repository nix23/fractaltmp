export const actions = {
    PUSH_MODAL_COMPONENT: "wasmboot/PUSH_MODAL_COMPONENT",
    MERGE_MODAL_COMPONENT_PROPS: "wasmboot/MERGE_MODAL_COMPONENT_PROPS",
    POP_MODAL_COMPONENT: "wasmboot/POP_MODAL_COMPONENT",
    RM_MODAL_COMPONENT: "wasmboot/RM_MODAL_COMPONENT",
    RM_ALL_MODAL_COMPONENTS: "wasmboot/RM_ALL_MODAL_COMPONENTS",
};

export const pushModalComponent = (modalId, props = {}) => {
    return {
        type: actions.PUSH_MODAL_COMPONENT,
        modalId: modalId,
        props: props,
    };
};

export const mergeModalComponentProps = (modalId, nextProps = {}) => {
    return {
        type: actions.MERGE_MODAL_COMPONENT_PROPS,
        modalId: modalId,
        nextProps: nextProps,
    };
};

export const popModalComponent = () => {
    return {
        type: actions.POP_MODAL_COMPONENT,
    };
};

export const rmModalComponent = (modalId) => {
    return {
        type: actions.RM_MODAL_COMPONENT,
        modalId: modalId,
    };
}

export const rmAllModalComponents = () => {
    return {
        type: actions.RM_ALL_MODAL_COMPONENTS,
    };
};