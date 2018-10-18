<?php
use app\models\AmostraTransmissor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="amostra-transmissor-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'data_coleta')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'cliente_id')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'tipo_deposito_id')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'quarteirao_id')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'numero_casa')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'numero_amostra')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'quantidade_larvas')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'quantidade_pupas')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'endereco')->textarea(['rows' => 6]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'observacoes')->textarea(['rows' => 6]) ?>
            </div>
        </div>
        <div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );

            echo Html::a(
                'Cancelar',
                array('/amostra-transmissor/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Amostra Transmissores')
            );
           ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>