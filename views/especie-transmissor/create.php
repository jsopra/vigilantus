<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\EspecieTransmissor $model
 */

$this->title = 'Cadastrar Espécie de Transmissor';
$this->params['breadcrumbs'][] = ['label' => 'Espécies de Transmissores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especie-transmissor-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]); ?>
</div>
