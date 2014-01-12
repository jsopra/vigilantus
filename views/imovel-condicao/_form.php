<?php

use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelCondicao $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="imovel-condicao-form">
	<?php $form = ActiveForm::begin(); ?>
    
		<?= $form->field($model, 'municipio_id')->dropDownList(Municipio::listData('nome')) ?>
		<?= $form->field($model, 'nome') ?>
		<?= $form->field($model, 'exibe_nome')->checkbox() ?>

		<div class="form-group">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
		</div>
    
	<?php ActiveForm::end(); ?>
</div>
