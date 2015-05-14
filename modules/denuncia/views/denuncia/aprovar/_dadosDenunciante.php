<?php
use yii\helpers\Html;
?>

<p>
    <strong><?= Html::activeLabel($model, 'nome') ?></strong><br>
    <?= $model->nome ? Html::encode($model->nome) : 'Não informado' ?>
</p>

<p>
    <strong><?= Html::activeLabel($model, 'email') ?></strong><br>
    <?= $model->email ? Html::encode($model->email) : 'Não informado' ?>
</p>

<p>
    <strong><?= Html::activeLabel($model, 'telefone') ?></strong><br>
    <?= $model->telefone ? Html::encode($model->telefone) : 'Não informado' ?>
</p>