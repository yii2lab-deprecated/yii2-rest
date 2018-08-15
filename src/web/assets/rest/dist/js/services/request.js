(function($){

    $.domain.rest.request = {

        send: function (request, successHandler) {
            if(empty(request)) {
                return;
            }
            var ajaxRequest = request;
            if(empty(ajaxRequest.url)) {
                ajaxRequest.url = $.domain.rest.router.forgeUrl(request.uri);
            }
            if(!is_object(ajaxRequest.headers)) {
                ajaxRequest.headers = {};
            }
            var token = $.domain.account.token.get();
            if(!empty(token)) {
                ajaxRequest.headers.authorization = token;
            }
            ajaxRequest.success = successHandler;

            if(empty(ajaxRequest.error )) {
                ajaxRequest.error = function (xhr, ajaxOptions, thrownError) {
                    if(xhr.status == 401) {
                        //$.domain.account.auth.authentication('77771111111', 'Wwwqqq111');

                        $.ajax({
                            url: app.env.url.frontend + 'user/auth/get-token',
                            //async: false,
                            success: function(data) {
                                $.domain.account.token.set(data);
                                ajaxRequest.error = function (xhr, ajaxOptions, thrownError) {
                                    $.ajax({
                                        url: app.env.url.frontend + 'user/auth/logout?redirect=user/auth/login',
                                        method: "post",
                                    });
                                }
                                $.domain.rest.request.send(ajaxRequest, successHandler);
                            }
                        });


                    }
                };
            }
            $.ajax(ajaxRequest);
        },

        get: function (uri, data, headers, successHandler) {
            var request = {
                method: "get",
                uri: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        post: function (uri, data, headers, successHandler) {
            var request = {
                method: "post",
                uri: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        put: function (uri, data, headers, successHandler) {
            var request = {
                method: "put",
                uri: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        delete: function (uri, data, headers, successHandler) {
            var request = {
                method: "delete",
                uri: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },

        forgeUrl: function (uri, version) {
            var baseUrl = this.baseUrl();
            return baseUrl + '/' + uri;
        },
        baseUrl: function (version) {
            var host = trim(app.env.url.api, '/');
            if(empty(version)) {
                version = app.api.defaultVersion;
            }
            return host + '/' + version;
        },
    };

})(jQuery);