<?php

$version = API_VERSION_STRING;

return [
	"GET {$version}/doc" => "rest/doc/index",
	"GET {$version}/doc/postman/<version>" => "rest/doc/postman",
];
