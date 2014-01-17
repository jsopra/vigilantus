<?php

use app\models\Municipio;
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
    
		<?= $form->field($model, 'nome') ?>
		<?= $form->field($model, 'login') ?>
		<?= $form->field($model, 'email')->input('email') ?>
		<?= $form->field($model, 'senha')->passwordInput() ?>
		<?= $form->field($model, 'senha2')->passwordInput() ?>
		<?= $form->field($model, 'usuario_role_id')->dropDownList(UsuarioRole::listDataNivelUsuario(Yii::$app->user->identity)) ?>
    
        <?php
        if (Yii::$app->user->checkAccess('Root')) : ?>
            <?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
            <?php
        endif; ?>

		<div class="form-group">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
