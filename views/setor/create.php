<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Setor $model
 */

$this->title = 'Cadastrar Setor';
$this->params['breadcrumbs'][] = ['label' => 'Setores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setor-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
