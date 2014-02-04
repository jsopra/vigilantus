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
                <?= $form->field($model, 'senha2')->passwordInput() ?>
            </div>
        </div>	
    
        <?php
        if (Yii::$app->user->checkAccess('Root')) : ?>
            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome'), ['prompt' => "Selecione..."]) ?>
                </div>
            </div>	
        <?php endif; ?>

		<div class="form-group">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
