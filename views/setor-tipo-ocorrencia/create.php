<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Tipo de Ocorrência "' . $setor->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Setores', 'url' => ['/setor/index']];
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Ocorrência dos Setores', 'url' => ['index?parentID=' . $setor->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'setor' => $setor,
    ]); ?>

</div>
