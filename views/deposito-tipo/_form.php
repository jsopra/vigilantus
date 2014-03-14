<?php
use app\models\DepositoTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="deposito-tipo-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'descricao')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'sigla')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'deposito_tipo_pai')->dropDownList(['' => 'Selecione...'] + DepositoTipo::listData('descricao')) ?>
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
                array('/deposito-tipo/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Deposito Tipos')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
