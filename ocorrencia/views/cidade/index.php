<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Municípios';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php foreach ($query->batch(3) as $batch) : ?>
<div class="row">
    <?php foreach ($batch as $municipio) : ?>
    <div class="col-sm-4">
        <?= Html::a(
            $municipio->nome . ' - ' . $municipio->sigla_estado,
            Url::to(['/ocorrencia/cidade/view', 'slug' => $municipio->slug])
        ) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php
endforeach;
