<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\OcorrenciaTipoProblema $model
 */

$this->title = 'Cadastrar Tipo de Problema de Ocorrência';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Problema de Ocorrência', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-tipo-problema-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
