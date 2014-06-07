<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DepositoTipo $model
 */

$this->title = 'Cadastrar Tipo de Depósito';
$this->params['breadcrumbs'][] = ['label' => 'Deposito Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposito-tipo-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
