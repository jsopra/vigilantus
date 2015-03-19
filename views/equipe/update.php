<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Equipe $model
 */

$this->title = 'Atualizar Equipe: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Equipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="equipe-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
