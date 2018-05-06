<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Supervisor na Equipe "' . $equipe->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Supervisores de Equipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipe-agente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'equipe' => $equipe,
    ]); ?>

</div>
