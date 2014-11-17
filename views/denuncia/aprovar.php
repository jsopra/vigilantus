<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Denuncia $model
 */

$this->title = 'Aprovar Denuncia #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Aprovar';
?>
<div class="denuncia-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('aprovar/_form', ['model' => $model]); ?>
</div>
