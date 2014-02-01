<?php

use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelTipo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="imovel-tipo-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'nome') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'sigla') ?>
            </div>
        </div>

		<div class="form-group vigilantus-form">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );
            
            echo Html::a(
                'Cancelar',
                array('/imovel-tipo/index'),
                array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir à lista de tipos de imóvel')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>

</div>
