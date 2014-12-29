<?php

use yii\helpers\Html;

$this->title = 'Cadastrar Módulo para Cliente "' . $cliente->municipio->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Módulos do Cliente', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-quarteirao-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
        'cliente' => $cliente,
        'modulos' => $modulos,
	]); ?>

</div>
