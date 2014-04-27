<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\FocoTransmissor $model
 */

$this->title = 'Cadastrar Foco de Transmissor';
$this->params['breadcrumbs'][] = ['label' => 'Focos de Transmissores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foco-transmissor-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
