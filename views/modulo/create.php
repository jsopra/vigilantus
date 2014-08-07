<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Modulo $model
 */

$this->title = 'Cadastrar Módulo';
$this->params['breadcrumbs'][] = ['label' => 'Módulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulo-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
