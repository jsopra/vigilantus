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

<?= $this->render('/resumo-rg/_capa', ['model' => $modelRg, 'ultimaAtualizacao' => Yii::$app->cache->get('ultima_atualizacao_resumo_cache_rg')], true); ?>
