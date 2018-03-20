<?php

namespace yii2lab\rest\web\helpers;

use common\enums\rbac\PermissionEnum;
use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\helpers\ModuleHelper;

class Menu implements MenuInterface {
	
	public function toArray() {
		$all = ModuleHelper::allByApp(FRONTEND);
		$items = $this->getVersionMenu($all);
		if(empty($items)) {
			return [];
		}
		if(count($items) > 1) {
			$item = [
				'items' => $items,
			];
		} else {
			$item = $items[0];
		}
		$item['label'] = 'API';
		$item['visible'] = YII_ENV_DEV;
		$item['access'] = [PermissionEnum::REST_CLIENT_ALL];
		return $item;
	}
	
	private function getVersionMenu($all) {
		$menu = [];
		foreach($all as $name => $config) {
			if($config['class'] == 'yii2lab\rest\web\Module') {
				$menu[] = [
					'label' => $this->parseVersion($name),
					'url' => $name,
					'module' => $name,
				];
			}
		}
		return $menu;
	}
	
	private function parseVersion($name) {
		preg_match('#(v[0-9]+)#', $name, $matches);
		return !empty($matches[1]) ? $matches[1] : $name;
	}
}