<?php
use yii\helpers\Html;

$this->title = 'Atualizar Quarteirão do Bairro: "' . $bairro->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Quarteirão de Bairro ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="bairro-quarteirao-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
        'bairro' => $bairro,
        'municipio' => $municipio,
        'quarteiroes' => $quarteiroes,
        'coordenadasQuarteiroes' => $coordenadasQuarteiroes
	]); ?>

</div>
