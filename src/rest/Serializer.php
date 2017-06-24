<?php

namespace yii2lab\rest\rest;

use yii\rest\Serializer as YiiSerializer;
use woop\foundation\helpers\Helper;

class Serializer extends YiiSerializer
{
	public $fieldType = [];
	
	private function assignType($value, $type)
	{
		if($type == 'time') {
			$value = Helper::timeForApi($value);
			//'Y-m-d\TH:i:s\Z'
			//\DateTime::ISO8601
		} elseif($type == 'integer') {
			$value = intval($value);
		} elseif($type == 'float') {
			$value = floatval($value);
		} elseif($type == 'boolean') {
			$value = !empty($value);
		}
		return $value;
	}
	
	private function serializeType($item)
	{
		if(empty($this->fieldType)) {
			return $item;
		}
		foreach($this->fieldType as $fieldName => $fieldType) {
			if(!array_key_exists($fieldName ,$item)) {
				continue;
			}
			if($fieldType == 'hide') {
				unset($item[$fieldName]);
			} elseif($fieldType == 'hideIfNull' && empty($item[$fieldName])) {
				unset($item[$fieldName]);
			} else {
				$item[$fieldName] = $this->assignType($item[$fieldName], $fieldType);
			}
		}
		return $item;
	}
	
	protected function serializeModel($model)
	{
		$item = parent::serializeModel($model);
		if(!empty($item)) {
			$item = $this->serializeType($item);
		}
		return $item;
	}
	
	protected function serializeModels(array $models)
	{
		$models = parent::serializeModels($models);
		foreach($models as &$item) {
			$item = $this->serializeType($item);
		}
		return $models;
	}
}
