<?php

use app\models\BairroCategoria;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Bairro $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="bairro-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_categoria_id')->dropDownList(['' => 'Selecione...'] + BairroCategoria::listData('nome')) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'nome') ?>
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
                array('/bairro/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de bairros')
            );

            ?>
            
       </div>

	<?php ActiveForm::end(); ?>
</div>
