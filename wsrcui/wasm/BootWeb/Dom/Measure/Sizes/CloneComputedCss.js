import SizesComputedCss from './ComputedCss';

class CloneComputedCss {
    copyComputedCss(source, target, SizesCore) {
        var me = this;

        var copyRecursive = (source, target) => {
            this.cloneComputedCss(source, target, SizesCore);

            for(var i = 0; i < source.childNodes.length; i++) {
                if(source.childNodes[i].nodeType != 1) 
                    continue;

                copyRecursive(source.childNodes[i], target.childNodes[i]);
                var childNodeCss = SizesComputedCss.getComputedCss(source.childNodes[i]);

                // Don't override 'auto' value
                if(/.*px.*/.test(childNodeCss.left))
                    target.childNodes[i].style.left = SizesCore.positionLeft(
                        source.childNodes[i]
                    ) + "px";
                if(/.*px.*/.test(childNodeCss.top))
                    target.childNodes[i].style.top = SizesCore.positionTop(
                        source.childNodes[i]
                    ) + "px";

                var childNodeRawSizes = SizesComputedCss.getUncomputedCss(
                    source.childNodes[i]
                );

                target.childNodes[i].style.width = SizesCore.outerWidth(
                    source.childNodes[i]
                ) + "px";
                if(parseInt(childNodeRawSizes.height, 10) != 0)
                    target.childNodes[i].style.height = SizesCore.outerHeight(
                        source.childNodes[i]
                    ) + "px";
            }
        }

        copyRecursive(source, target);
    }

    cloneComputedCss(source, target, SizesCore) {
        var camelize = function(text) {
            return text.replace(/-+(.)?/g, function(match, chr) {
                return chr ? chr.toUpperCase() : '';
            });
        };

        var sourceCompCss = SizesCore.getComputedCss(source);

        for(var prop in sourceCompCss) {
            if(prop == "cssText")
                continue;

            var propName = camelize(prop);
            if(target.style[propName] != sourceCompCss[propName])
                target.style[propName] = sourceCompCss[propName];
        }

        this.reclone(sourceCompCss, target);
    }

    reclone(sourceCompCss, target) {
        // Some properties could be overwritten by further rules.
        // For example in FF/IE borders are overwritten by some from further rules.
        var propsToReclone = ["font", "fontSize", "fontWeight", "lineHeight"];
        var borderProps = ["Width", "Color", "Style"];
        var borderSides = ["Left", "Right", "Top", "Bottom"];
        for(var i = 0; i < borderProps.length; i++) {
            for(var j = 0; j < borderSides.length; j++)
                propsToReclone.push("border" + borderSides[j] + borderProps[i]);
        }

        for(var i = 0; i < propsToReclone.length; i++) {
            var propName = propsToReclone[i];
            if(typeof sourceCompCss[propName] != "undefined" &&
                target.style[propName] != sourceCompCss[propName])
                target.style[propName] = sourceCompCss[propName];
        }
    }
};

export default new CloneComputedCss();