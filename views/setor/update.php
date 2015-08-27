<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Setor $model
 */

$this->title = 'Atualizar Setor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setores', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="setor-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
