import Util from '../Util';

// @todo -> Decide if this is required???
//       -> Or it is better to extend all Components from CustomComponent???
//       -> (this.ref("Name")); + add Css className prefix to all classNames???
class ReactUtil {
    ref(Component, refIds) {
        if(!Array.isArray(refIds))
            refIds = [refIds];

        refIds.map((refId) => {
            Component[refId + "Ref"] = (elOrComponent) => {
                Component[refId] = elOrComponent;
            };
        });
    }
};

export default new ReactUtil();