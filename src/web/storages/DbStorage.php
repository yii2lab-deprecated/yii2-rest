<?php

namespace yii2lab\rest\web\storages;

use Yii;
use yii\db\Connection;
use yii\db\IntegrityException;
use yii\db\Query;
use yii\di\Instance;
use yii\web\NotFoundHttpException;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\rest\domain\entities\RestEntity;

/**
 * Class DbStorage
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class DbStorage extends Storage
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';
    /**
     * @var string the name of the DB table that stores the data.
     * The table should be pre-created as follows:
     *
     * ~~~
     * CREATE TABLE IF NOT EXISTS rest (
     *     id INT(11) NOT NULL AUTO_INCREMENT,
     *     tag VARCHAR(24) NOT NULL,
     *     module_id VARCHAR(64) NOT NULL,
     *     request LONGBLOB NOT NULL,
     *     response LONGBLOB NOT NULL,
     *     method VARCHAR(8),
     *     endpoint VARCHAR(128),
     *     description LONGTEXT,
     *     status VARCHAR(3),
     *     stored_at INT(11) DEFAULT NULL,
     *     favorited_at INT(11) DEFAULT NULL,
     *     PRIMARY KEY (id),
     *     UNIQUE KEY tag (tag, module_id),
     *     KEY module_id (module_id)
     * );
     * ~~~
     */
    public $tableName = '{{%rest}}';

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @inheritdoc
     */
    public function exists($tag)
    {
	    try {
		    /** @var RestEntity $one */
		    $one = Yii::$domain->rest->rest->oneByTag($tag);
		    return true;
	    } catch(NotFoundHttpException $e) {
		    return false;
	    }
        /*return (new Query())
            ->from($this->tableName)
            ->andWhere(['tag' => $tag])
            ->andWhere(['module_id' => $this->module->id])
            ->exists($this->db);*/
    }

    /**
     * @inheritdoc
     */
    protected function readData($tag, &$request, &$response)
    {
        try {
	        /** @var RestEntity $one */
	        $one = Yii::$domain->rest->rest->oneByTag($tag);
	        $request = $one->request;
	        $response = $one->response;
	        return true;
        } catch(NotFoundHttpException $e) {
	        return false;
        }
	    /*
	    $query = (new Query())
            ->select(['request', 'response'])
            ->from($this->tableName)
            ->andWhere(['tag' => $tag])
            ->andWhere(['module_id' => $this->module->id]);
		prr($query->one($this->db),1,1);
        if ($row = $query->one($this->db)) {
            $request = unserialize($row['request']);
            $response = unserialize($row['response']);
            return true;
        } else {
            return false;
        }*/
    }

    /**
     * @inheritdoc
     */
    protected function writeData($tag, $request, $response)
    {
     
    	if(!empty($this->exists($tag))) {
            return false;
        }
	    //prr($tag,1,1);
        $data = [
	        'tag' => $tag,
	        'module_id' => $this->module->id,
	        'request' => serialize($request),
	        //'response' => $response,
        ];
	    Yii::$domain->rest->rest->create($data);
	    
        /*try {
	        $this->db->createCommand()
		        ->insert($this->tableName, [
			        'tag' => $tag,
			        'module_id' => $this->module->id,
			        'request' => serialize($request),
			        'response' => serialize($response),
		        ])
		        ->execute();
        } catch (IntegrityException $e) {
    		
        }*/
    }

    /**
     * @inheritdoc
     */
    protected function removeData($tag)
    {
	    //prr($tag,1,1);
	    Yii::$domain->rest->rest->removeByTag($tag);
        /*$this->db->createCommand()
            ->delete($this->tableName, [
                'tag' => $tag,
                'module_id' => $this->module->id,
            ])
            ->execute();*/
    }

    /**
     * @inheritdoc
     */
    protected function readHistory()
    {
	    $collection = Yii::$domain->rest->rest->allHistory();
	    $array = ArrayHelper::toArray($collection);
	    $array = ArrayHelper::index($array, 'tag');
	    return $array;
	    
    	/*$query = (new Query())
            //->select(['tag', 'method', 'endpoint', 'description', 'status', 'time' => 'stored_at'])
            ->from($this->tableName)
            ->andWhere(['module_id' => $this->module->id])
            ->andWhere('stored_at IS NOT NULL')
            ->orderBy(['tag' => SORT_ASC])
            ->indexBy('tag');

        $rows = $query->all($this->db);
        foreach ($rows as &$row) {
            unset($row['tag']);
        }
        unset($row);
        return $rows;*/
    }

    /**
     * @inheritdoc
     */
    protected function writeHistory($rows)
    {
	    //prr('writeHistory');
	   // prr($rows,1,1);
	    foreach($rows as $tag => $row) {
	    	if(!$this->exists($tag)) {
			    /** @var RestEntity $entity */
			    $entity = Yii::$domain->rest->rest->oneByTag($tag);
			    $entity->favorited_at = TIMESTAMP;
			    // prr($entity,1,1);
			    Yii::$domain->rest->rest->update($entity);
			    //prr($entity,1,1);
		    }
		    
	    }
    	/*$this->db->transaction(function () use ($rows) {
            $old = $this->readHistory();
            foreach (array_diff_key($old, $rows) as $tag => $row) {
                $this->db->createCommand()
                    ->update($this->tableName, ['stored_at' => null], [
                        'tag' => $tag,
                        'module_id' => $this->module->id,
                    ])
                    ->execute();
            }
            foreach (array_diff_key($rows, $old) as $tag => $row) {
                $row['stored_at'] = $row['time'];
                unset($row['time']);
                $this->db->createCommand()
                    ->update($this->tableName, $row, [
                        'tag' => $tag,
                        'module_id' => $this->module->id,
                    ])
                    ->execute();
            }
        });*/
    }

    /**
     * @inheritdoc
     */
    protected function readCollection()
    {
	    $collection = Yii::$domain->rest->rest->allFavorite();
	    $array = ArrayHelper::toArray($collection);
	    $array = ArrayHelper::index($array, 'tag');
	    return $array;
    	
    	/*$query = (new Query())
            //->select(['tag', 'method', 'endpoint', 'description', 'status', 'time' => 'favorited_at'])
            ->from($this->tableName)
            ->andWhere(['module_id' => $this->module->id])
            ->andWhere('favorited_at IS NOT NULL')
            ->orderBy(['tag' => SORT_ASC])
            ->indexBy('tag');

        $rows = $query->all($this->db);
        foreach ($rows as &$row) {
            unset($row['tag']);
        }
        unset($row);

        return $rows;*/
    }

    /**
     * @inheritdoc
     */
    protected function writeCollection($rows)
    {
	    prr('writeCollection');
    	prr($rows,1,1);
        /*$this->db->transaction(function () use ($rows) {
            $old = $this->readCollection();
            foreach (array_diff_key($old, $rows) as $tag => $row) {
                $this->db->createCommand()
                    ->update($this->tableName, ['favorited_at' => null], [
                        'tag' => $tag,
                        'module_id' => $this->module->id,
                    ])
                    ->execute();
            }
            foreach (array_diff_key($rows, $old) as $tag => $row) {
                $row['favorited_at'] = $row['time'];
                unset($row['time']);
                $this->db->createCommand()
                    ->update($this->tableName, $row, [
                        'tag' => $tag,
                        'module_id' => $this->module->id,
                    ])
                    ->execute();
            }
        });*/
    }

    /**
     * @inheritdoc
     */
    public function clearHistory()
    {
	    Yii::$domain->rest->rest->clearHistory();
	    return true;
    	/*return $this->db->transaction(function () {
            return parent::clearHistory();
        });*/
    }

    /**
     * @inheritdoc
     */
    public function importCollection($data)
    {
        return $this->db->transaction(function () use ($data) {
            return parent::importCollection($data);
        });
    }
}