<?php

namespace yii2lab\rest\domain\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\rest\domain\entities\RestEntity;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\repositories\ar\RestRepository;
use yii2lab\store\StoreFile;

/**
 * Class RestService
 *
 * @package yii2lab\rest\domain\services
 *
 * @property RestRepository $repository
 */
class RestService extends BaseActiveService {
	
	public function normalizeTag() {
		$q = Query::forge();
		$q->andWhere('favorited_at');
		$collection = $this->repository->all($q);
		$this->repository->truncate();
		$this->batchInsert($collection);
	}
	
	private function getStoreFile() {
		$file = COMMON_DATA_DIR . DS . 'rest-client' . DS . 'favorite' . '.php';
		return new StoreFile($file);
	}
	
	
	public function exportCollection() {
		$collection = $this->repository->allFavorite();
		$rows = ArrayHelper::toArray($collection);
		$storeFile = $this->getStoreFile();
		$storeFile->save($rows);
	}
	
	public function importCollection() {
		$storeFile = $this->getStoreFile();
		$collection = $storeFile->load();
		$this->repository->truncate();
		$this->batchInsert($collection);
	}
	
	public function batchInsert($collection) {
		$collection = $this->repository->forgeEntity($collection);
		foreach($collection as $entity) {
			$this->repository->insert($entity);
		}
	}
	
	public function addToCollection($tag) {
		/** @var RestEntity $entity */
		$entity = $this->oneByTag($tag);
		$entity->favorited_at = TIMESTAMP;
		$this->update($entity);
	}
	
	public function allFavorite() {
		$apiVersion = MiscHelper::currentApiVersion();
		return $this->repository->allFavorite($apiVersion);
	}
	
	public function allHistory() {
		$apiVersion = MiscHelper::currentApiVersion();
		return $this->repository->allHistory($apiVersion);
	}
	
	public function oneByTag($tag) {
		$moduleId = MiscHelper::moduleId();
		$query = Query::forge();
		$query->where([
			'tag' => $tag,
			'module_id' => $moduleId,
		]);
		return $this->repository->one($query);
	}
	
	public function removeByTag($tag) {
		
		$entity = $this->oneByTag($tag);
		//prr($entity,1,1);
		return $this->repository->delete($entity);
	}
	
	public function clearHistory() {
		$moduleId = MiscHelper::moduleId();
		return $this->repository->clearHistory($moduleId);
	}
	
	public function createOrUpdate($data) {
		/** @var RestEntity $entity */
		$entity = $this->repository->forgeEntity($data);
		try {
			$entityOld = $this->oneByTag($entity->tag);
			$entityOld->load($data);
			//prr($entityOld,1,1);
			$this->update($entityOld);
		} catch(NotFoundHttpException $e) {
			$entity = $this->create($entity->toArray());
		}
		return $entity;
	}
}
