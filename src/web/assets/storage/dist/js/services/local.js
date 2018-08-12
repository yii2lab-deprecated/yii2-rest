(function($){

    $.domain.storage.local = {
        get: function (key) {
            key = this.generateGlobalKey(key);
            var jsonValue = localStorage.getItem(key);
            return JSON.parse(jsonValue);
        },
        set: function (key, value) {
            key = this.generateGlobalKey(key);
            var jsonValue = JSON.stringify(value, null, 2);
            localStorage.setItem(key, jsonValue);
        },
        remove: function (key) {
            key = this.generateGlobalKey(key);
            localStorage.removeItem(key);
        },
        generateGlobalKey: function (key) {
            return 'localData-' + convertToHex(key);
        },
    };

})(jQuery);