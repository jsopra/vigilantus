<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ImovelCondicao $model
 */

$this->title = 'Cadastrar Condição de Imóvel';
$this->params['breadcrumbs'][] = ['label' => 'Condições de Imóveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imovel-condicao-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_form', ['model' => $model]) ?>
</div>
