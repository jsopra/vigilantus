<?php

use app\models\BairroCategoria;
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Bairro $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="bairro-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
		<?= $form->field($model, 'bairro_tipo_id')->dropDownList(BairroCategoria::listData('nome')) ?>
		<?= $form->field($model, 'nome') ?>

		<div class="form-group">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
		</div>

	<?php ActiveForm::end(); ?>
</div>
