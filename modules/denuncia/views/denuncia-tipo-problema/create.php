<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DenunciaTipoProblema $model
 */

$this->title = 'Cadastrar Tipo de Problema de Denúncia';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Problema de Denúncia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-tipo-problema-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
