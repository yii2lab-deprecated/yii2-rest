<?php

namespace yii2lab\rest\web\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2lab\helpers\Behavior;
use yii2lab\navigation\domain\widgets\Alert;
use yii2lab\rest\domain\helpers\RouteHelper;
use yii2lab\rest\web\models\ImportForm;

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
        if ($this->module->storage->addToCollection($tag)) {
        	Yii::$domain->navigation->alert->create('Request was added to collection successfully.', Alert::TYPE_SUCCESS);
            return $this->redirect(['request/create', 'tag' => $tag]);
        } else {
            throw new NotFoundHttpException('Request not found.');
        }
    }

    public function actionUnlink($tag)
    {
        if ($this->module->storage->removeFromCollection($tag)) {
	        Yii::$domain->navigation->alert->create('Request was removed from collection successfully.', Alert::TYPE_SUCCESS);
            return $this->redirect(['request/create']);
        } else {
            throw new NotFoundHttpException('Request not found.');
        }
    }

    public function actionExport()
    {
        return Yii::$app->response->sendContentAsFile(
            Json::encode($this->module->storage->exportCollection()),
            $this->module->id .'-' . date('Ymd-His') . '.json'
        );
    }
	
	public function actionExportPostman21()
	{
		preg_match('#(v\d)#', $this->module->id, $matches);
		$apiVersionString = $matches[0];
		return Yii::$app->response->sendContentAsFile(
			Json::encode(RouteHelper::allRoutesForPostman21($apiVersionString), JSON_PRETTY_PRINT),
			$this->module->id .'-' . date('Ymd-His') . '.json'
		);
	}
    
    public function actionImport()
    {
        $model = new ImportForm();
        if (
            $model->load(Yii::$app->request->post()) &&
            ($count = $model->save($this->module->storage)) !== false
        ) {
            if ($count) {
	            Yii::$domain->navigation->alert->create("{$count} requests was imported to collection successfully.", Alert::TYPE_SUCCESS);
            } else {
	            Yii::$domain->navigation->alert->create("New requests not found.", Alert::TYPE_WARNING);
            }
            return $this->redirect(['request/create']);
        }
        return $this->render('import', [
            'model' => $model,
        ]);
    }
}