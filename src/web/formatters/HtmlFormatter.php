<?php

namespace yii2lab\rest\web\formatters;

use yii\helpers\Html;

/**
 * Class HtmlFormatter
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class HtmlFormatter extends RawFormatter
{
    /**
     * @inheritdoc
     */
    public function format($record)
    {
        return Html::tag('pre',
            Html::tag('code',
                Html::encode($record->content),
                ['id' => 'response-content', 'class' => 'html']
            )
        );
    }
}