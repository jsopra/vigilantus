<?php

use yii\helpers\Html;

$this->title = 'Atualizar Ocorrência: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="bairro-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_formUpdate', [
        'model' => $model,
    ]); ?>
</div>
