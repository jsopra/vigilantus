<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\FocoTransmissor $model
 */

$this->title = 'Atualizar Foco de Transmissor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Focos de Transmissores', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="foco-transmissor-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
