<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Resumo de Indicadores';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php
echo Tabs::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'RG',
            'active' => true,
            'content' => $this->render('/resumo-rg/_capa', ['model' => $modelRg], true),
            'options' => ['id' => 'rg']
        ],
        
        [
            'label' => 'Focos',
            'content' => $this->render('/resumo-focos/_capa', ['model' => $modelFoco], true),
            'options' => ['id' => 'focos']
        ],
    ],
]);