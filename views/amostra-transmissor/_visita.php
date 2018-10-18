<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\web\View;
use yii\widgets\DetailView;
use Yii\helpers\Url;


$this->title = 'Visita em Imóvel';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
       
    ]

]) ?>