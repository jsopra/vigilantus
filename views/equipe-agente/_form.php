<?php
use app\models\Equipe;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="equipe-agente-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'codigo')->textInput() ?>
            </div>
            <div class="col-xs-5">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'ativo')->checkbox() ?>
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
                array('/equipe-agentes/index', 'parentID' => $equipe->id),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de agentes da equipe')
            );

            ?>

       </div>

    <?php ActiveForm::end(); ?>

</div>
