<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Quarteirão do Bairro "' . $bairro->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Quarteirões de Bairros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
        'bairro' => $bairro,
        'municipio' => $municipio,
        'quarteiroes' => $quarteiroes,
        'coordenadasQuarteiroes' => $coordenadasQuarteiroes
	]); ?>

</div>
