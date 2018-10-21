<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Resumo de Trabalho de Campo do agente';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mapa-area-tratamento-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?> para ciclo <span style="color: #797979;"><?= Html::encode($ciclo->nome) ?></span> </h1>

</div>

<br />