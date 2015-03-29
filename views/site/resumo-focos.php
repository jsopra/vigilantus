<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<?= $this->render('_menuHome', ['municipio' => $cliente->municipio]); ?>

<?= $this->render('/resumo/_focos', ['model' => $modelFoco], true); ?>
