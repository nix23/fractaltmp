// @todo -> Implement
class File {
    cancelBodyDrops() {
        $("body").on("dragover", function(event) {
            event.preventDefault();
            event.stopPropagation();

            return false;
        });
        $("body").on("drop", function(event) {
            event.preventDefault();
            event.stopPropagation();

            return false;
        });
    }
};

export default new File();
