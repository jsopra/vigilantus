<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<?= $this->render('_menuHome', [
    'municipio' => $cliente->municipio,
    'qtdeVerde' => $qtdeVerde,
    'qtdeVermelho' => $qtdeVermelho,
    'diasVerde' => $diasVerde,
    'diasVermelho' => $diasVermelho,
]); ?>

<?= $this->render('/resumo-focos/_capa', ['model' => $modelFoco], true); ?>
