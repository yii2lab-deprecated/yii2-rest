<?php

namespace yii2lab\rest\web\formatters;

use yii\base\BaseObject;
use yii\helpers\Html;

/**
 * Class RawFormatter
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RawFormatter extends BaseObject
{
    /**
     * @param \yii2lab\rest\web\models\ResponseRecord $record
     * @return string
     */
    public function format($record)
    {
        return Html::tag('pre',
            Html::tag('code',
                Html::encode($record->content),
                ['id' => 'response-content']
            )
        );
    }

    /**
     * @param \Exception $exception
     * @return string
     */
    protected function warn($exception)
    {
        return Html::tag('div', '<strong>Warning!</strong> ' . $exception->getMessage(), [
            'class' => 'alert alert-warning',
        ]);
    }
}