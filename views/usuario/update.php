<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Usuario $model
 */

$this->title = 'Atualizar Usuário: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="usuario-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
