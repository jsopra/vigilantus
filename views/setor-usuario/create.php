<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Setor nos Setores "' . $setor->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Agentes de Equipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="****">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'setor' => $setor,
    ]); ?>

</div>
