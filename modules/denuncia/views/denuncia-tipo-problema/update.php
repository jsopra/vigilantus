<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DenunciaTipoProblema $model
 */

$this->title = 'Atualizar Tipo de Problema de Denúncia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Problema de Denúncia', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="denuncia-tipo-problema-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
