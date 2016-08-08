<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\web\View;
use yii\widgets\DetailView;
use Yii\helpers\Url;
use app\models\DepositoTipo;
use kartik\select2\Select2;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;


$this->title = 'Análise Laboratorial';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'data_criacao',
            'value' => $model->getFormattedAttribute('data_criacao'),
        ],
        [
            'attribute' => 'data_coleta',
            'value' => $model->getFormattedAttribute('data_coleta'),
        ],
        [
            'attribute' => 'tipo_deposito_id',
            'filter' => DepositoTipo::listData('descricao'),
            'value' => $model->tipoDeposito->sigla ? $model->tipoDeposito->sigla : $model->tipoDeposito->descricao,
        ],
        [
            'attribute' => 'quarteirao_id',
            'value' => $model->bairroQuarteirao->numero_sequencia,
        ],
        'endereco',
        'observacoes',
        'numero_casa',
        'numero_amostra',
        'quantidade_larvas',
        'quantidade_pupas',
        [
            'attribute' => 'foco_transmissor_id',
            'format' => 'raw',
            'value' => $model->foco_transmissor_id ? Html::a(
                'Ver foco',
                ['/mapa/tratamento-foco', 'TratamentoFocoMapForm[foco_id]' => $model->foco_transmissor_id],
                ['class'=>'link']
            ) : null,
        ],
    ]

]) ?>


<?php if($model->foco === null || $submitting) : ?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'foco')->dropDownList([0 => 'Não', 1 => 'Sim'], ['prompt' => 'Selecione…']) ?>
        </div>
    </div>

    <div class="row focoTrue">
        <div class="col-xs-3">
            <?= $form->field($model, 'especie_transmissor_id')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + EspecieTransmissor::listData('nome'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            );
            ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, 'planilha_imovel_tipo_id')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + ImovelTipo::listData('descricao_sigla'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            );
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'observacoes')->textarea(['rows' => 5]) ?>
        </div>
    </div>

    <div class="form-group form-actions">
    <?php
        echo Html::submitButton(
            $model->isNewRecord ? 'Salvar' : 'Concluir Análise',
            ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
    );
?>
<?php else : ?>
    <?php
        echo Html::a(
            'Voltar',
            array('/amostra-transmissor/index'),
            array('class'=>'btn btn-flat success','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Amostra Transmissors')
        );
    ?>
<?php endif; ?>


<?php
$view = Yii::$app->getView();
$script = '
$(document).ready(function(){

    $(".focoTrue").hide();

    $("#amostratransmissor-foco").change(function(){

        if ($(this).val() == "1") {
            $(".focoTrue").show();
        } else {
            $(".focoTrue").hide();
        }
    });

    $("#amostratransmissor-foco").change();
});
';
$view->registerJs($script);
