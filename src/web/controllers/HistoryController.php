<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2lab\helpers\Behavior;

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
            Yii::$app->session->setFlash('success', 'Request was removed from history successfully.');
            return $this->redirect(['request/create']);
        } else {
            throw new NotFoundHttpException('Request not found.');
        }
    }

    public function actionClear()
    {
        if ($count = $this->module->storage->clearHistory()) {
            Yii::$app->session->setFlash('success', 'History was cleared successfully.');
        } else {
            Yii::$app->session->setFlash('warning', 'History already is empty.');
        }
        return $this->redirect(['request/create']);
    }
}