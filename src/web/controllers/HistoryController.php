<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2lab\helpers\Behavior;
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
        if ($this->module->storage->removeFromHistory($tag)) {
	        Yii::$domain->navigation->alert->create('Request was removed from history successfully.', Alert::TYPE_SUCCESS);
            return $this->redirect(['request/create']);
        } else {
            throw new NotFoundHttpException('Request not found.');
        }
    }

    public function actionClear()
    {
        if ($count = $this->module->storage->clearHistory()) {
	        Yii::$domain->navigation->alert->create('History was cleared successfully.', Alert::TYPE_SUCCESS);
        } else {
	        Yii::$domain->navigation->alert->create('History already is empty.', Alert::TYPE_WARNING);
        }
        return $this->redirect(['request/create']);
    }
}