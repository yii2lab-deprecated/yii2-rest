<?php

namespace yii2lab\rest\console\controllers;

use api\enums\ApiVersionEnum;
use yii2lab\console\base\Controller;
use yii2lab\console\helpers\input\Select;
use yii2lab\rest\domain\helpers\ApiDocHelper;
use yii2lab\console\helpers\Output;

/**
 * Api Doc module.
 */
class DocController extends Controller {
	
	/**
	 * Generate API documentation
	 */
	public function actionGenerate($version = 5) {
		$versionList = ApiVersionEnum::getApiVersionNumberList();
		$versionList = array_combine($versionList, $versionList);
		$selected = Select::display('Select package', $versionList);
		$version = Select::getFirstValue($selected);
		ApiDocHelper::generate($version);
		Output::block('Success generated');
	}
	
}
