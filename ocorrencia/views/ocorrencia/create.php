<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Ocorrencia $model
 */

$this->title = 'Cadastrar Ocorrência';
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
