<?php
use app\models\Equipe;
use app\models\Usuario;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="equipe-agente-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'usuario_id')->dropDownList(['' => 'Selecione...'] + Usuario::listData('nome')) ?>
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
