<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Equipe $model
 */

$this->title = 'Cadastrar Equipe';
$this->params['breadcrumbs'][] = ['label' => 'Equipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipe-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
