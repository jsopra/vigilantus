<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Armadilha';
$this->params['breadcrumbs'][] = ['label' => 'Armadilha', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_form', ['model' => $model]); ?>
</div>
