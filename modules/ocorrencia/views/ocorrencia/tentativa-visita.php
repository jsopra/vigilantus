<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

$this->title = 'Informar tentativa de averiguação de Ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Informar tentativa de averiguação';
?>
<div class="ocorrencia-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([]); ?>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($modelForm, 'agente_id')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + \app\helpers\AgenteHelper::getPorEquipe(),
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]
            ); ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($modelForm, 'data')->input('date', ['class' => 'form-control input-datepicker']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-8">
            <?= $form->field($modelForm, 'observacoes')->textArea(); ?>
        </div>
    </div>

    <div class="form-group form-actions">
            <?php
            echo Html::submitButton(
                'Cadastrar',
                ['class' => 'btn btn-flat success']
            );

            echo Html::a(
                'Cancelar',
                array('/ocorrencia/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Ocorrências')
            );
            ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
