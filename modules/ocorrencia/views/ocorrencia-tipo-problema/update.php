<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\OcorrenciaTipoProblema $model
 */

$this->title = 'Atualizar Tipo de Problema de Ocorrência: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Problema de Ocorrência', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="ocorrencia-tipo-problema-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
