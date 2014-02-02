<?php

use app\models\Bairro;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\BoletimRgSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="boletim-rg-fechamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <?php if (Yii::$app->user->checkAccess('Root')) : ?>
        <?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
        <?php endif; ?>

        <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome')) ?>

        <div class="form-group">
            <?= Html::submitButton('Gerar Planilha', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
