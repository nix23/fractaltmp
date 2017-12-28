export const actions = {
    CREATE_FORM_SEARCH_DATA_BY_ID: "wasmboot/CREATE_FORM_SEARCH_DATA_BY_ID",
    RM_FORM_SEARCH_DATA_BY_ID: "wasmboot/RM_FORM_SEARCH_DATA_BY_ID",
    SET_FORM_SEARCH_VALUE: "wasmboot/SET_FORM_SEARCH_VALUE",
    LOADING_ON: "wasmboot/LOADING_ON",
    LOADING_OFF: "wasmboot/LOADING_OFF",
    TRIGGER_INPUT_INTERACTION: "wasmboot/TRIGGER_INPUT_INTERACTION",
};

// @todo -> Check if is not already in state???
//       -> (Restore store from I-th state + time-travel)
export const createFormSearchDataById = (formId) => {
    return {
        type: actions.CREATE_FORM_SEARCH_DATA_BY_ID,
        formId: formId,
    };
};

export const rmFormSearchDataById = (formId) => {
    return {
        type: actions.RM_FORM_SEARCH_DATA_BY_ID,
        formId: formId,
    };
};

export const setFormSearchValue = (formId, nextValue) => {
    return {
        type: actions.SET_FORM_SEARCH_VALUE,
        formId: formId,
        nextValue: nextValue,
    };
};

export const loadingOn = (formId) => {
    return {
        type: actions.LOADING_ON,
        formId: formId,
    };
};

export const loadingOff = (formId) => {
    return {
        type: actions.LOADING_OFF,
        formId: formId,
    };
};

export const triggerInputInteraction = (formId) => {
    return {
        type: actions.TRIGGER_INPUT_INTERACTION,
        formId: formId,
    };
};