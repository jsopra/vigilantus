<?php
use yii\helpers\Html;

$this->title = 'Atualizar Agente de Equipe: "' . $equipe->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Agentes de Equipes ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="bairro-quarteirao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'equipe' => $equipe,
    ]); ?>

</div>
