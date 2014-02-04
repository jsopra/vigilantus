<?php
use yii\helpers\Html;

$this->title = 'Resumo do Reconhecimento Geográfico';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('/resumo-rg/_capa', ['resumoBairros' => $resumoBairros, 'resumoTiposImoveis' => $resumoTiposImoveis]); ?>