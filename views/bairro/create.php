<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Bairro $model
 */

$this->title = 'Cadastrar Bairro';
$this->params['breadcrumbs'][] = ['label' => 'Bairros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bairro-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
