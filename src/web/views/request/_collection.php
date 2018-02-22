<?php
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $activeTag
 * @var array $items
 */
?>
<div class="rest-request-collection">
    <ul id="collection-list" class="request-list">
        <?php foreach ($items as $group => $rows): ?>
            <li>
                <div class="request-list-group">
                    <?= Html::encode($group) ?>
                    <?= Html::tag('span', count($rows), ['class' => 'counter']) ?>
                </div>
                <ul>
                    <?php foreach ($rows as $tag => $row): ?>
	                    <?= $this->render('_item', [
		                    'type' => 'collection',
                            'tag' => $tag,
		                    'row' => $row,
		                    'activeTag' => $activeTag,
	                    ]) ?>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
        <li>
            <div class="request-list-group">
                Tools
            </div>
            <div>
		        <?php if ($items): ?>
			        <?= Html::a('Export Collection', ['collection/export'], [
				        'class' => 'btn btn-block btn-default',
				        'title' => 'Export collection to file.'
			        ]) ?>
		        <?php endif; ?>
		        <?= Html::a('Import Collection', ['collection/import'], [
			        'class' => 'btn btn-block btn-default',
			        'title' => 'Import collection from file.'
		        ]) ?>
            </div>
        </li>
    </ul>
</div>