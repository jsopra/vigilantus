<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DepositoTipo;
use app\models\Bairro;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;
use app\widgets\GridView;
use app\helpers\models\ImovelHelper;
use app\helpers\models\FocoTransmissorHelper;
use yii\helpers\Url;

$this->title = 'Relatório de Focos por Bairro';
$this->params['breadcrumbs'][] = $this->title;

$modelForm = $model;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row" id="dadosPrincipais">

                <div class="col-xs-2">
                    <?= $form->field($model, 'ano')->input('number') ?>
                </div>
                
                <div class="col-xs-3">
                    <?= $form->field($model, 'especie_transmissor_id')->dropDownList(EspecieTransmissor::listData('nome'), ['prompt' => 'Todas']) ?>
                </div>

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Gerar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<br />

<?php
echo GridView::widget([
    'dataProvider' => $model->dataProviderAreasFoco,
    'columns' => [
		[
		    'header' => 'Bairro',
		    'value' => function ($model, $index, $widget) {
		        return $model[0];
		    },
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 1, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Jan',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[1];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[1] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 2, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Fev',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[2];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[2] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 3, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Mar',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[3];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[3] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 4, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Abr',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[4];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[4] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 5, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Mai',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[5];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[5] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 6, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Jun',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[6];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[6] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 7, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Jul',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[7];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[7] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 8, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Ago',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[8];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[8] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 9, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Set',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[9];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[9] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 10, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Out',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[10];
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[10] === 0;
	        },
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 11, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Nov',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[11];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[11] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'mes' => 12, 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Dez',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[12];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[12] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
		[
			'class' => 'app\extensions\grid\ModalColumn',
	        'iconClass' => 'icon-search opacity50',
	        'modalId' => 'focos-detalhes',
	        'modalAjaxContent' => function ($model, $index, $widget) {
	            return Url::toRoute(['relatorio/focos-bairro-data', 'idBairro' => $model[14], 'ano' => $model[15], 'idEspecieTransmissor' => $model[16]]);
	        },
	        'requestType' => 'GET',
	        'header' => 'Total',
	        'linkTitle' => 'Ver Focos',
	        'value' => function ($model, $index, $widget) {
	            return $model[13];
	        },
	        'hideLinkExpression' => function ($model, $index, $widget) {
	            return $model[13] === 0;
	        },
	        'customScript' => '$(document).pjax("a", "#pjax-container");',
	        'options' => [
	            'width' => '5%',
	        ]
		],
	]
]); 