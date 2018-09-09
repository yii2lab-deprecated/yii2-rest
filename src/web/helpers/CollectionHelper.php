<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\extension\yii\helpers\ArrayHelper;

class CollectionHelper {
	
	public static function prependCollection($collection) {
		$closure = function ($row) {
			if (preg_match('|[^/]+|', ltrim($row->endpoint, '/'), $m)) {
				return $m[0];
			} else {
				return 'common';
			}
		};
		$collection = ArrayHelper::group($collection, $closure);
        return $collection;
	}
	
	
	
}