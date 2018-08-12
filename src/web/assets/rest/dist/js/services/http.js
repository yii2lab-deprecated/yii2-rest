(function($){

    $.domain.rest.http = {
        send: function (request, successHandler, errorHandler) {
            if(empty(request)) {
                return;
            }
            var ajaxRequest = request;
            if(!is_object(ajaxRequest.headers)) {
                ajaxRequest.headers = {};
            }

            ajaxRequest.success = successHandler;
            ajaxRequest.error = errorHandler;
            $.ajax(ajaxRequest);
        },
        get: function (uri, data, headers, successHandler) {
            var request = {
                method: "get",
                url: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        post: function (uri, data, headers, successHandler) {
            var request = {
                method: "post",
                url: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        put: function (uri, data, headers, successHandler) {
            var request = {
                method: "put",
                url: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },
        delete: function (uri, data, headers, successHandler) {
            var request = {
                method: "delete",
                url: uri,
                data: data,
                headers: headers,
            };
            this.send(request, successHandler);
        },

    };

})(jQuery);