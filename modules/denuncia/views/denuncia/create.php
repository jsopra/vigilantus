<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Denuncia $model
 */

$this->title = 'Cadastrar Denúncia';
$this->params['breadcrumbs'][] = ['label' => 'Denuncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
