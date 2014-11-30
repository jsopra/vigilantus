<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Municipio $model
 */

$this->title = 'Cadastrar Município';
$this->params['breadcrumbs'][] = ['label' => 'Municipios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="municipio-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
