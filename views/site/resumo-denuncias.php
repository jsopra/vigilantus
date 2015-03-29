<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<?= $this->render('_menuHome', ['municipio' => $cliente->municipio]); ?>

<?= $this->render('/resumo/_denuncias', [
    'model' => $modelDenuncias,
    'diasVerde' => $diasVerde,
    'diasVermelho' => $diasVermelho,
    'qtdeVerde' => $qtdeVerde,
    'qtdeAmarelo' => $qtdeAmarelo,
    'qtdeVermelho' => $qtdeVermelho,
], true); ?>
