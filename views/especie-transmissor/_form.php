<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\EspecieTransmissor $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="especie-transmissor-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
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
                array('/especie-transmissor/index'),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de espécies de transmissores')
            );

            ?>
            
       </div>

    <?php ActiveForm::end(); ?>
</div>
