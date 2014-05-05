<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\EspecieTransmissor $model
 */

$this->title = 'Atualizar Espécie de Transmissor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Espécies de Transmissores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="especie-transmissor-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', ['model' => $model]); ?>
</div>
