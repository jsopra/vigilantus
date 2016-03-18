<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\CasoDoenca $model
 */

$this->title = 'Cadastrar Caso Doenca';
$this->params['breadcrumbs'][] = ['label' => 'Caso Doencas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caso-doenca-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
