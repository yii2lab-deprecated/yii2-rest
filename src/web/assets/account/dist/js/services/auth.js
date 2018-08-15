(function($){

    $.domain.account.auth = {
        authentication: function (login, password) {
            var localSuccessHandler = function (loginEntity) {
                $.domain.account.token.set(loginEntity.token);
            };
            var request = {
                method: "post",
                uri: 'auth',
                data: {
                    login: login,
                    password: password,
                },
                async: false,
            };
            $.domain.rest.request.send(request, localSuccessHandler);
        },
        info: function() {
            var successHandler = function (loginEntity) {
                console.log(loginEntity);
            };
            $.domain.rest.request.get('auth', null, null, successHandler);
        },
        logout: function() {
            $.domain.account.token.remove();
        },
    };

})(jQuery);