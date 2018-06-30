<?php

namespace yii2lab\rest\domain\repositories\ar;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;

class RestRepository extends BaseActiveArRepository {
	
	protected $modelClass = 'yii2lab\rest\domain\models\Rest';
	
	public function allFavorite($version) {
		$query = $this->prepareQuery();
		$query->where('module_id', "rest-v{$version}");
		$query->andWhere(['>', 'favorited_at', '0']);
		$collection = $this->all($query);
		return $this->forgeEntity($collection);
	}
	
	public function allHistory($version) {
		$query = $this->prepareQuery();
		$query->where('module_id', "rest-v{$version}");
		$query->andWhere(['favorited_at' => null]);
		$collection = $this->all($query);
		return $this->forgeEntity($collection);
	}
}
