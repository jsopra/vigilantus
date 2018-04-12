<?php

use yii\helpers\Html;

$this->title = 'Atualizar Semana Epidemiológica: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Semana Epidemiológica', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="semana-epidemiologica-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
