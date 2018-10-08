<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\navigation\domain\widgets\Alert;

/**
 * Class HistoryController
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class HistoryController extends Controller
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
		        'delete' => ['post'],
		        'clear' => ['post'],
	        ]),
        ];
    }
    public function actionDelete($tag)
    {
	    \App::$domain->rest->rest->removeByTag($tag);
	    \App::$domain->navigation->alert->create('Request was removed from history successfully.', Alert::TYPE_SUCCESS);
	    return $this->redirect(['request/create']);
    }

    public function actionClear()
    {
	    \App::$domain->rest->rest->clearHistory();
    	\App::$domain->navigation->alert->create('History was cleared successfully.', Alert::TYPE_SUCCESS);
        return $this->redirect(['request/create']);
    }
}