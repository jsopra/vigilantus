<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroQuarteirao $model
 */

$this->title = 'Atualizar Quarteirão de Bairro: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quarteirão de Bairro ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="bairro-quarteirao-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
