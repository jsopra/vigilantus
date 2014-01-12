<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\BairroTipoSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bairro-tipo-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'municipio_id') ?>

		<?= $form->field($model, 'nome') ?>

		<?= $form->field($model, 'data_cadastro') ?>

		<?= $form->field($model, 'data_atualizacao') ?>

		<?php // echo $form->field($model, 'inserido_por') ?>

		<?php // echo $form->field($model, 'atualizado_por') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
