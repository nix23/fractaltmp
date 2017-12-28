class Reducer {
    reduce(actions, reducers, initialState) => {
        return (state = initialState, action) => {
            for(let actionType in reducers) {
                if(actionType == action.type)
                    return reducers[actionType](
                        state, action
                    );
            }

            return state;
        };
    }
};

export default new Reducer();
