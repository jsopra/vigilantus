<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DepositoTipo $model
 */

$this->title = 'Atualizar Tipo de Depósito: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Deposito Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="deposito-tipo-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
