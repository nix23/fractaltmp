import Str from '~/wasm/Util/Str';

class Query {
    byId(id) {
        return document.getElementById(id);
    }

    byClass(root, className) {
        return root.querySelectorAll("." + className);
    }

    byQuery(root, selector) {
        var firstChar = Str.trim(selector)[0];
        if(firstChar == ">") {
            var postfix = selector.substr(2, selector.length - 1);
            var items = root.querySelectorAll(postfix);
            var directChilds = [];

            for(var i = 0; i < items.length; i++) {
                if(items[i].parentNode == root)
                    directChilds.push(items[i]);
            }

            return directChilds;
        }

        return root.querySelectorAll(selector);
    }

    getData(item) {
        var attrs = [];
        for(var i = 0; i < item.attributes.length; i++) {
            var attr = item.attributes[i];
            if(/^data-/.test(attr.nodeName))
                attrs.push(attr.nodeName);
        }

        return attrs;
    }

    rmByQuery(root, selector) {
        var items = this.byQuery(root, selector);
        for(var i = 0; i < items.length; i++) {
            var item = items[i];
            item.parentNode.removeChild(item);
        }
    }
};

export default new Query();