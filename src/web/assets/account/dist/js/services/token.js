(function($){

    $.domain.account.token = {
        storageKey: 'account.auth.identity.token',
        get: function () {
            return $.domain.storage.local.get(this.storageKey);
        },
        set: function (loginEntity) {
            $.domain.storage.local.set(this.storageKey, loginEntity);
        },
        remove: function () {
            return $.domain.storage.local.remove(this.storageKey);
        },
    };

})(jQuery);