<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
* Handles the creation of table `rest`.
*/
class m170605_214311_create_rest_table extends Migration
{
	public $table = '{{%rest}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey(),
			'tag' => $this->string(24),
			'module_id' => $this->string(64),
			'request' => $this->text(),
			'response' => $this->text(),
			'method' => $this->string(8),
			'endpoint' => $this->string(128),
			'description' => $this->text(),
			'status' => $this->string(3),
			'stored_at' => $this->integer(11),
			'favorited_at' => $this->integer(11),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['tag', 'module_id']);
	}

}
