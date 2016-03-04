<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\web\View;
use yii\widgets\DetailView;
use Yii\helpers\Url;
use app\models\DepositoTipo;


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
            'attribute' => 'data_atualizacao',
            //'value' => $model->getFormattedAttribute('data_atualizacao'),
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
        ]

]) ?>


<?php if($model->foco === null) : ?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'foco')->dropDownList([0 => 'Não', 1 => 'Sim'], ['prompt' => 'Selecione…']) ?>
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




