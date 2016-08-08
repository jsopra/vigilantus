<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Estados do Brasil';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php foreach ($query->batch(3) as $batch) : ?>
<div class="row">
    <?php foreach ($batch as $estado) : ?>
    <div class="col-sm-4">
        <?= Html::a(
            $estado->nome . ' (' . $estado->uf . ')',
            Url::to(['/ocorrencia/cidade/index', 'uf' => strtolower($estado->uf)])
        ) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php
endforeach;
