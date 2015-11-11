<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Municípios';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php foreach ($query->batch(2) as $batch) : ?>
<div class="row">
    <?php foreach ($batch as $municipio) : ?>
    <div class="col-sm-6">
        <?= Html::a(
            Html::encode($municipio->nome . ' - ' . $municipio->sigla_estado),
            Url::to(['/ocorrencia/cidade/view', 'slug' => $municipio->slug])
        ) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php
endforeach;
