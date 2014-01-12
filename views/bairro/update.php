<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Bairro $model
 */

$this->title = 'Atualizar Bairro: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bairros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="bairro-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
