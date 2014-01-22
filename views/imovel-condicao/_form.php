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

		<div class="form-group vigilantus-form">
			<?php
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );
            
            echo Html::a(
                'Cancelar',
                array('/imovel-condicao/index'),
                array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir à lista de condições de imóvel')
            );

            ?>
            
       </div>
    
	<?php ActiveForm::end(); ?>
</div>
