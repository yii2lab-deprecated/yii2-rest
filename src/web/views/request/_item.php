<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string        $activeTag
 * @var string        $tag
 * @var array         $row
 */

$options = ['data-tag' => $tag];

if ($row['status'] < 300) {
	Html::addCssClass($options, 'success');
} elseif ($row['status'] < 400) {
	Html::addCssClass($options, 'info');
} elseif ($row['status'] < 500) {
	Html::addCssClass($options, 'warning');
} else {
	Html::addCssClass($options, 'danger');
}

if ($row['method'] == 'get' || $row['method'] == 'head' || $row['method'] == 'options') {
	$methodColor = '#63a8e2';
} elseif ($row['method'] == 'put' || $row['method'] == 'patch') {
	$methodColor = '#22bac4';
} elseif ($row['method'] == 'post') {
	$methodColor = '#6cbd7d';
} elseif ($row['method'] == 'delete') {
	$methodColor = '#d26460';
} else {
	$methodColor = '#fff';
}

if ($tag === $activeTag) {
	Html::addCssClass($options, 'active');
}
if(isset($row['request'])) {
	$row['request'] = unserialize($row['request']);
}

?>
<li <?= Html::renderTagAttributes($options) ?>>
    <a href="<?= Url::to(['request/create', 'tag' => $tag]) ?>">
        <small class="request-name">
            
                        <span class="request-method label label-info" style="width: 100px !important; background-color: <?= $methodColor ?>;">
                            <?= Html::encode($row['method']) ?>
	                        
	                        <?php if (!empty($row['request']['authorization'])): ?>
                            &nbsp;
                            <span class="glyphicon glyphicon-lock" title="Authentication required"></span>
	                        <?php endif; ?>
                        </span>
                        <span class="request-endpoint">
                            &nbsp;
                            <?= Html::encode($row['endpoint']) ?>
                        </span>
                    </small>
		<?php if (!empty($row['description'])): ?>
            <span class="request-description">
                            <?= Html::encode($row['description']) ?>
                        </span>
		<?php endif; ?>
    </a>
    <div class="actions">
		<?php if (isset($row['in_collection']) && !$row['in_collection']): ?>
			<?= Html::a('&plus;', ['collection/link', 'tag' => $tag], [
				'data-method' => 'post',
				'title' => 'Move to collection.',
			]) ?>
		<?php endif; ?>
		<?= Html::a('&times;', [($type == 'history' ? 'history/delete' : 'collection/unlink'), 'tag' => $tag], [
			'data-method' => 'post',
			'title' => 'Remove from ' . $type . '.',
		]) ?>
    </div>
</li>