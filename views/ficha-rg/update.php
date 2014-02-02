<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 */

$this->title = 'Atualizar Boletim de RG nº ' . $model->folha;
$this->params['breadcrumbs'][] = ['label' => 'Boletim de Reconhecimento Geográfico', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="bairro-tipo-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', ['model' => $model]); ?>

</div>
