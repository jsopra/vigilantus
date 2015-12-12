<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Municípios em ' . $estado->nome;

$this->params['breadcrumbs'][] = ['label' => 'Estados do Brasil', 'url' => ['/ocorrencia/estado/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php foreach ($query->batch(3) as $batch) : ?>
<div class="row">
    <?php foreach ($batch as $municipio) : ?>
    <div class="col-sm-4">
        <?= Html::a(
            Html::encode($municipio->nome . ' - ' . $municipio->sigla_estado),
            Url::to(['/ocorrencia/cidade/view', 'slug' => $municipio->slug])
        ) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php
endforeach;
