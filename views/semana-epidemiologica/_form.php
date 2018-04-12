<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="semana-epidemiologica-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
            <div class="col-xs-4 bairro-hide">
                <?= $form->field($model, 'inicio')->input('date', ['class' => 'form-control input-datepicker']) ?>
            </div>
            <div class="col-xs-4 bairro-hide">
                <?= $form->field($model, 'fim')->input('date', ['class' => 'form-control input-datepicker']) ?>
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
                array('/doenca/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Semanas Epidemiológicas')
            );
            ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
