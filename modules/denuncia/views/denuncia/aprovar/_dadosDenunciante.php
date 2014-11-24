<?php
use yii\helpers\Html;
?>

<br />

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'nome'); ?>
        <p class="form-control-static"><?php echo $model->nome; ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'email'); ?>
        <p class="form-control-static"><?php echo $model->email; ?></p>
    </div>

</div>

<br />

<div class="row">
    <div class="col-xs-5">
        <?= Html::activeLabel($model, 'telefone'); ?>
        <p class="form-control-static"><?php echo $model->telefone; ?></p>
    </div>
</div>