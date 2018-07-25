<?php
/**
 * @var \yii\web\View $this
 * @var \yii\base\Model $model
 * @var \yii\widgets\ActiveForm $form
 * @var string $keyAttribute
 * @var string $valueAttribute
 * @var string $activeAttribute
 */
$id = uniqid('params-');
$fieldOptions = [
    'options' => ['class' => 'form-group form-group-sm'],
];
$i = 1;
?>
<div id="<?= $id ?>" class="params-list">
    <table class="table">
        <tbody>
            <?php foreach (array_keys($model->$keyAttribute) as $i): ?>
            <tr data-index="<?= $i ?>">
                <td class="column-check">
                    <?= $form->field($model, $activeAttribute . "[$i]")->checkbox(['tabindex' => -1], false) ?>
                </td>
                <td class="column-key">
                    <?= $form->field($model, $keyAttribute . "[$i]", $fieldOptions)->textInput([
                        'placeholder' => $model->getAttributeLabel($keyAttribute),
                    ]) ?>
                </td>
                <td class="column-value">
                    <?= $form->field($model, $valueAttribute . "[$i]", $fieldOptions)->textInput([
                        'placeholder' => $model->getAttributeLabel($valueAttribute),
                    ]) ?>
                </td>
                <td class="column-actions">
                    <button type="button" class="close" tabindex="-1">
                        <span>&times;</span>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
// Удаление и добавление строк параметров
$this->registerJs(<<<'JS'

window.addRow1 = function(th) {
	var page = $(th).text();
	
	var rows = $('#request-query table tr');
	
	
	var row = $('#request-query table tr:last');
	var keyInput = row.find('.column-key input');
	var ValueInput = row.find('.column-value input');
	
	keyInput.focus();
	
	keyInput.val('page');
	ValueInput.val(page);
}

window.addRow2 = function() {
	var curRow = $(this).parents('tr').first();
        var i = parseInt(curRow.data('index'));
        var newRow = curRow.clone();
        newRow.attr('data-index', i + 1);
        newRow.find('input').each(function(){
            $(this).attr('name', $(this).attr('name').replace(
                '[' + i + ']',
                '[' + (i + 1) + ']'
            ));
        });
        newRow.insertAfter(curRow);
        updateCounter($(this).parents('.tab-pane').first());
}

$('.params-list')
    .on('focus', 'tr:last input', window.addRow2)
    .on('blur', 'tr input', function() {
        updateCounter($(this).parents('.tab-pane').first());
    })
    .on('click', 'button.close', function() {
        var tab = $(this).parents('.tab-pane').first();
        $(this).parents('tr').remove();
        updateCounter(tab);
    });

function calcCountParams(params) {
	var countParams = 0;
    params.each(function(){
     var isFilled = false;
    	$(this).find('input.form-control').each(function(){
            if($(this).val() != '') {
            	isFilled = true;
            }
        });
    	if(isFilled) {
    		countParams++;
    	}
     });
    return countParams;
}

function updateCounter(tab) {
    var params = tab.find('tr');
	var paramsCount = calcCountParams(params); //var paramsCount = params.length - 1;
    var tabId = tab.attr('id');
    var counter = $('a[href="#' + tabId + '"] > .counter');
    counter.text(paramsCount);
    if (paramsCount) {
        counter.removeClass('hidden');
    } else {
        counter.addClass('hidden');
    }
}

JS
);
$this->registerCss(<<<'CSS'

.params-list {
    margin: -8px;
}
.params-list .form-group {
    margin-bottom: 0;
}
.params-list .form-group .help-block {
    margin: 0;
}
.params-list td {
    border-top: none !important;
}
.params-list td.column-check,
.params-list td.column-actions {
    width: 30px;
    vertical-align: middle !important;
}
.params-list td.column-key {
    width: 30%;
}
.params-list tr:last-child button.close {
    display: none;
}

CSS
);