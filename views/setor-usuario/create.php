<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Usuário no Setor "' . $setor->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Usuários dos Setores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'setor' => $setor,
    ]); ?>

</div>
