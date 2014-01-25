<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroQuarteirao $model
 */

$this->title = 'Cadastrar Quarteirão de Bairro';
$this->params['breadcrumbs'][] = ['label' => 'Quarteirões de Bairros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
