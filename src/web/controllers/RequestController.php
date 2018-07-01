<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\web\Controller;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\rest\domain\entities\RestEntity;
use yii2lab\rest\web\helpers\AdapterHelper;
use yii2lab\rest\web\helpers\RestHelper;
use yii2lab\rest\web\models\RequestEvent;
use yii2lab\rest\web\models\RequestForm;
use yii2lab\rest\web\models\ResponseEvent;
use yii2lab\rest\web\models\ResponseRecord;
use yii2lab\rest\web\Module;

/**
 * Class RequestController
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RequestController extends Controller
{
    /**
     * @var \yii2lab\rest\web\Module
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'create';

    public function actionCreate($tag = null)
    {
        /** @var RequestForm $model */
        $model = Yii::createObject(RequestForm::class);
	    $record = new ResponseRecord();
        if($tag !== null) {
	        /** @var RestEntity $restEntity */
	        $restEntity = Yii::$domain->rest->rest->oneByTag($tag);
	        $model->setAttributes($restEntity->request);
	        
        } elseif(Yii::$app->request->isPost) {
	        $model->load(Yii::$app->request->post());
	        if($model->validate()) {
		        $record = $this->send($model);
		        $data = [
			        'tag' => $tag,
			        'module_id' => $this->module->id,
			        'request' => $model->toArray(),
		        ];
		        Yii::$domain->rest->rest->createOrUpdate($data);
	        }
        }
	
	    $model->addEmptyRows();
        $history = $this->module->storage->getHistory();
        $collection = $this->module->storage->getCollection();

        foreach ($history as $_tag => &$item) {
            $item['in_collection'] = isset($collection[$_tag]);
        }
        unset($item);
        // TODO Grouping will move to the config level
        $collection = ArrayHelper::group($collection, function ($row) {
            if (preg_match('|[^/]+|', ltrim($row['endpoint'], '/'), $m)) {
                return $m[0];
            } else {
                return 'common';
            }
        });

        return $this->render('create', [
            'tag' => $tag,
            'baseUrl' => rtrim($this->module->baseUrl, '/') . '/',
            'model' => $model,
            'record' => $record,
            'history' => $history,
            'collection' => $collection,
        ]);
    }

    /**
     * @param RequestForm $model
     * @return ResponseRecord
     */
    protected function send(RequestForm $model)
    {
        $this->module->trigger(Module::EVENT_ON_REQUEST, new RequestEvent([
            'form' => $model,
        ]));
	
	    $requestEntity = AdapterHelper::createRequestEntityFromForm($model);
	
	    //$response = RestHelper::sendRequest($requestEntity);
        $record = RestHelper::sendRequest($requestEntity);

        $this->module->trigger(Module::EVENT_ON_RESPONSE, new ResponseEvent([
            'form' => $model,
            'record' => $record,
        ]));

        return $record;
    }
}