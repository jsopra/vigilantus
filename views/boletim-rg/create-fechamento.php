<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 */

$this->title = 'Preencher Fechamento de Boletim de RG';
$this->params['breadcrumbs'][] = ['label' => 'Fechamento de Boletim de Reconhecimento Geográfico', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-tipo-create">
	<h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form-fechamento', ['model' => $model]); ?>
</div>
