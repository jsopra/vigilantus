<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Agendar Visitas';
$this->params['breadcrumbs'][] = ['label' => 'Agendar Visitas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semana-epidemiologica-agendar">
    <h1><?= Html::encode($this->title) ?></h1>

	<div class="bairro-form">

	    <?php $form = ActiveForm::begin(); ?>

	        <div class="row">
	            <div class="col-xs-6">
	                <strong>Cliclo:</strong> <?= $model->nome ?>
	            </div>
	        </div>

	        <div id="map"  style="height: 450px; width: 100%;"></div>

	        <div class="form-group form-actions">
	            <?php
	            echo Html::submitButton(
	                'Salvar',
	                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
	            );

	            echo Html::a(
	                'Cancelar',
	                array('/semana-epidemiologica/index'),
	                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de semanas epidemiológicas')
	            );

	            ?>

	       </div>

	    <?php ActiveForm::end(); ?>
	</div>
</div>
