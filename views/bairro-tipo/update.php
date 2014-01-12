<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 */

$this->title = 'Atualizar Tipo de Bairro: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Bairro', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="bairro-tipo-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', ['model' => $model]); ?>

</div>
