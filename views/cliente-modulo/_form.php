<?php

use app\models\Cliente;
use app\models\Modulo;
use app\models\ClienteModulo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;

/**
 * @var yii\web\View $this
 * @var app\models\ClienteModulo $model
 * @var yii\widgets\ActiveForm $form
 */
?>


<div class="cliente-modulo-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-2">
                <?= $form->field($model, 'modulo_id')->dropDownList(Modulo::listData('nome'), ['prompt' => 'Selecione…']) ?>
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
                array('/cliente-modulo/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de Módulos do Cliente')
            );

            ?>

       </div>

	<?php ActiveForm::end(); ?>

</div>
