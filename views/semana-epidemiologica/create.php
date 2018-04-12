<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Semana Epidemiológica';
$this->params['breadcrumbs'][] = ['label' => 'Semana Epodemiológica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semana-epidemiologica-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', ['model' => $model]); ?>
</div>
