<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Cliente $model
 */

$this->title = 'Atualizar Cliente: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="cliente-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
