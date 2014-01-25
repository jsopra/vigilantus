<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Usuario $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Alterar Senha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-change-password">
	<h1><?= Html::encode($this->title) ?></h1>
    <div class="usuario-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'senha')->passwordInput() ?>
                </div>
                <div class="col-xs-3">
                    <?= $form->field($model, 'senha2')->passwordInput() ?>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="form-group">
                <?php
                echo Html::submitButton(
                    'Alterar Senha',
                    ['class' => 'btn btn-flat primary']
                ) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
