<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelTipo $model
 */

$this->title = 'Atualizar Tipo de Imóvel: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Imóvel', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="imovel-tipo-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_form', ['model' => $model]) ?>
</div>
