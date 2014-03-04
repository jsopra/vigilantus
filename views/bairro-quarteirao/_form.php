<?php

use app\models\Municipio;
use app\models\Bairro;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BairroQuarteirao $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bairro-quarteirao-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_id')->dropDownList(['' => 'Selecione...'] + Bairro::listData('nome')) ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao')->textInput() ?>
            </div>
            <div class="col-xs-2">
                <?= $form->field($model, 'numero_quarteirao_2')->textInput() ?>
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
                array('/bairro-quarteirao/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de quarteirões de bairros')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>

</div>