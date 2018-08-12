(function($){

    $.domain.rest.router = {
        forgeUrl: function (uri, version) {
            var baseUrl = this.baseUrl(version);
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