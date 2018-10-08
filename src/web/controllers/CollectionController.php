<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\web\Controller;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\navigation\domain\widgets\Alert;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;

/**
 * Class CollectionController
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class CollectionController extends Controller
{
    /**
     * @var \yii2lab\rest\web\Module
     */
    public $module;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
	        'verb' => Behavior::verb([
		        'link' => ['post'],
		        'unlink' => ['post'],
	        ]),
        ];
    }

    public function actionLink($tag)
    {
	    \App::$domain->rest->rest->addToCollection($tag);
	    \App::$domain->navigation->alert->create('Request was added to collection successfully.', Alert::TYPE_SUCCESS);
	    return $this->redirect(['request/create', 'tag' => $tag]);
    }

    public function actionUnlink($tag)
    {
	    \App::$domain->rest->rest->removeByTag($tag);
	    \App::$domain->navigation->alert->create('Request was removed from collection successfully.', Alert::TYPE_SUCCESS);
	    return $this->redirect(['request/create']);
    }
	
	public function actionExportPostman($postmanVersion)
	{
		$apiVersion = MiscHelper::currentApiVersion();
		$collectionName = MiscHelper::collectionNameFormatId();
		$fileName = $collectionName .'-' . date('Y-m-d-H-i-s') . '.json';
		return Yii::$app->response->sendContentAsFile(
			PostmanHelper::generateJson($apiVersion, $postmanVersion),
			$fileName
		);
	}
	
}