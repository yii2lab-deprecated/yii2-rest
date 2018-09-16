<?php

namespace tests\rest;

use yii2lab\test\base\_application\common\enums\app\ApiVersionEnum;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\test\Test\Rest;

class DefaultTest extends Rest {
	
	protected $version = ApiVersionEnum::VERSION_DEFAULT;

    /*public function testMainPage() {
        $this->tester->sendGET(null);
        $this->tester->seeResponseCodeIs(400);

        $expectedBody = [
            "name" => "Bad Request",
            "message" => "No API version specified",
            "code" => 0,
            "status" => 400,
            "type" => "Exception",
            //"versions" => 1"],
        ];

        $this->tester->seeResponseContainsJson($expectedBody);
    }

    public function testVersionPage() {
        $versionList = ApiVersionEnum::getApiVersionNumberList();
        foreach($versionList as $version) {
            $this->tester->sendGET($this->url('v' . $version));
            $this->tester->seeResponseCodeIs(200);
            $expectedBody = [
                "title" => "string",
                "header" => "string",
                "text" => "string",
            ];
            $this->tester->seeResponseMatchesJsonType($expectedBody);
        }
    }*/
	
	/*public function testAuth() {
		$this->tester->sendGET($this->url . 'auth');
		$this->tester->seeResponseCodeIs(401);
	}
	
	public function testSuccessAuth() {
		$this->tester->sendPOST($this->url . 'auth', ['login'=>'77771111111', 'password'=>'Wwwqqq111']);
		$this->tester->seeResponseCodeIs(200);
	}
	
	public function testBadAuth() {
		$this->tester->sendPOST($this->url . 'auth', ['login'=>'77771111111', 'password'=>'Wwwqqq222']);
		$this->tester->seeResponseCodeIs(422);
	}*/
	
}
