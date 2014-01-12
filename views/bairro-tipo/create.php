<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 */

$this->title = 'Cadastrar Tipo de Bairro';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Bairro', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-create">
	<h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]); ?>
</div>
