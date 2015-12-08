<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AmostraTransmissor $model
 */

$this->title = 'Cadastrar Amostra Transmissor';
$this->params['breadcrumbs'][] = ['label' => 'Amostra Transmissors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amostra-transmissor-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
