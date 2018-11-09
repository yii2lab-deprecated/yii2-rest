<?php

namespace yii2lab\rest\console\controllers;

use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\input\Select;
use yii2lab\rest\domain\helpers\ApiDocHelper;
use yii2lab\extension\console\helpers\Output;
use yii2lab\rest\domain\helpers\MiscHelper;

/**
 * Api Doc module.
 */
class DocController extends Controller {
	
	/**
	 * Generate API documentation
	 */
	public function actionGenerate() {
		$versionList = MiscHelper::getAllVersions();
		$versionList = array_combine($versionList, $versionList);
		$selected = Select::display('Select package', $versionList);
		$version = Select::getFirstValue($selected);
		ApiDocHelper::generate($version);
		Output::block('Success generated');
	}
	
}
