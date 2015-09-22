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

$this->title = 'Relatório de Focos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

            <div class="row" id="dadosPrincipais">
                <div class="col-xs-3">
                    <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Todos']) ?>
                </div>

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
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header' => 'Mês',
            'value' => function ($model, $index, $widget) {
                return FocoTransmissorHelper::getMes($model->data_coleta);
            },
        ],
        [
            'header' => 'Bairro',
            'value' => function ($model, $index, $widget) {
                return $model->bairroQuarteirao->bairro->nome;
            },
        ],
        [
            'format' => 'raw',
            'header' => 'Data da Coleta',
            'value' => function ($model, $index, $widget) {
                return $model->getFormattedAttribute('data_coleta');
            },
        ],
        [
            'attribute' => 'tipo_deposito_id',
            'value' => function ($model, $index, $widget) {
                return $model->tipoDeposito->sigla ? $model->tipoDeposito->sigla : $model->tipoDeposito->descricao;
            }
        ],
        [
            'format' => 'raw',
            'header' => 'Forma',
            'value' => function ($model, $index, $widget) {
                return FocoTransmissorHelper::getForma($model);
            },
        ],
        [
            'attribute' => 'imovel_id',
            'value' => function ($model, $index, $widget) {
                return $model->imovel ? ImovelHelper::getEndereco($model->imovel) : 'Vinculado à Quarteirão';
            },
            'options' => ['style' => 'width: 30%']
        ],
    ],
]);
