<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AmostraTransmissor $model
 */

$this->title = 'Atualizar Amostra Transmissor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Amostra Transmissors', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="amostra-transmissor-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
