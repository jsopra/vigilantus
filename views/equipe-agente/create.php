<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Agente na Equipe "' . $equipe->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Agentes de Equipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipe-agente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'equipe' => $equipe,
    ]); ?>

</div>
