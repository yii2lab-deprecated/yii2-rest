<?php
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $activeTag
 * @var array $items
 */
?>
<div class="rest-request-history">
    <ul id="history-list" class="request-list">
        <?php foreach (array_reverse($items, true) as $tag => $row): ?>
	        <?= $this->render('_item', [
		        'type' => 'history',
                'tag' => $tag,
                'row' => $row,
		        'activeTag' => $activeTag,
	        ]) ?>
        <?php endforeach; ?>
    </ul>

    <?php if ($items): ?>
        <div>
            <?= Html::a('Clear History', ['history/clear'], [
                'data' => ['method' => 'post', 'confirm' => 'Are you sure?'],
                'class' => 'btn btn-block btn-danger',
                'title' => 'Full clear history.'
            ]) ?>
        </div>
    <?php endif; ?>
</div>