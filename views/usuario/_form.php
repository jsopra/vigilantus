<?php

use app\models\UsuarioRole;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Usuario $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="usuario-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'nome') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'login') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'email')->input('email') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'usuario_role_id')->dropDownList(['' => 'Selecione...'] + UsuarioRole::listDataNivelUsuario(Yii::$app->user->identity)) ?>
            </div>
        </div>

		<div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'senha')->passwordInput() ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'confirmacao_senha')->passwordInput() ?>
            </div>
        </div>

        <?= $form->field($model, 'recebe_email_ocorrencia')->checkbox() ?>

		<div class="form-group">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? Html::encode('Cadastrar') : Html::encode('Atualizar'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
