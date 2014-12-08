<?php

use yii\helpers\Html;

$this->title = 'Atualizar Configuração #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Configurações', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="configuracao-cliente-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
