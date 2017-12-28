class Console {
    constructor() {
        this.initConsole();
    }

    // @todo -> Init App console
    initConsole() {
        let consoleLog = function(...args) {
            // @todo -> If prod not use!
            console.log("");
            console.log("********************************************");
            console.log.apply(this, args);
            console.log("********************************************");
            console.log("");
        };

        window.cl = consoleLog;
    }
};

export default new Console();