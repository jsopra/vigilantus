<?php

use yii\helpers\Html;

$this->title = 'Atualizar Armadilha: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Armadilhas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';

$model->loadCoordenadas();
?>
<div class="bairro-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
