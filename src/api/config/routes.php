<?php

$version = API_VERSION_STRING;

return [
    "{$version}/mock/<route:.+>" => "rest/mock/index",
	"GET {$version}/doc" => "rest/doc/index",
	"GET {$version}/doc/html" => "rest/doc/html",
	"GET {$version}/doc/<action:(export-collection|import-collection|normalize-collection)>" => "rest/doc/<action>",
	"GET {$version}/doc/postman/<version>" => "rest/doc/postman",
];
