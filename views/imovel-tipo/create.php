<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelTipo $model
 */

$this->title = 'Cadastrar Tipo de Imóvel';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Imóvel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imovel-tipo-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_form', ['model' => $model]) ?>
</div>
