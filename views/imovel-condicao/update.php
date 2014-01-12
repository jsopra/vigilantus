<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelCondicao $model
 */

$this->title = 'Atualizar Condição de Imóvel: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Condições de Imóveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="imovel-condicao-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_form', ['model' => $model]) ?>
</div>
