<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_menuHome', []); ?>

<?= $this->render('/resumo-focos/_capa', ['model' => $modelFoco], true); ?>