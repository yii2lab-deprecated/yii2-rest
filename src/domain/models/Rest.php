<?php

namespace yii2lab\rest\domain\models;

use yii\db\ActiveRecord;
use yii2lab\db\domain\behaviors\serialize\SerializeBehavior;

class Rest extends ActiveRecord
{
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%rest}}';
	}
	
	public function behaviors()
	{
		return [
			'rulesJson' => [
				'class' => SerializeBehavior::class,
				'attributes' => ['request'/*, 'response'*/],
			],
		];
	}
	
	public function fields() {
		$fields = parent::fields();
		$fields['response'] = function ($model) {
			return [];
		};
		return $fields;
	}
	
}
