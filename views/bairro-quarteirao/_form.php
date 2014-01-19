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
                <?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome')) ?>
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

		<div class="form-group vigilantus-form">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            );
            
            echo Html::a(
                'Cancelar',
                array('/bairro-quarteirao/index'),
                array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir à lista de quarteirões de bairros')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>

</div>