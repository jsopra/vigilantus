<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<?= $this->render('_menuHome', ['municipio' => $municipio]); ?>

<?= $this->render('/resumo-rg/_capa', ['model' => $modelRg, 'ultimaAtualizacao' => Yii::$app->cache->get('ultima_atualizacao_cache_rg')], true); ?>