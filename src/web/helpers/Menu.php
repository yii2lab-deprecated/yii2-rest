<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\common\helpers\ModuleHelper;
use yii2lab\rest\domain\enums\RestPermissionEnum;

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
		$item['access'] = [RestPermissionEnum::CLIENT_ALL];
		return $item;
	}
	
	private function getVersionMenu($all) {
		$menu = [];
		foreach($all as $name => $config) {
			$config = ClassHelper::normalizeComponentConfig($config);
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