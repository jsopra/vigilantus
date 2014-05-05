<?php

use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\GoogleMapsAPIHelper;

/**
 * @var yii\web\View $this
 * @var app\models\BairroQuarteirao $model
 */

$this->title = 'Atualizar Quarteirão de Bairro: "' . $parentObject->nome . '"';
$this->params['breadcrumbs'][] = ['label' => 'Quarteirão de Bairro ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';

$model->loadCoordenadas();
if(!$model->getIsNewRecord() && !$model->coordenadasJson && $model->coordenadas) 
    $model->coordenadasJson = GoogleMapsAPIHelper::arrayToCoordinatesJson($model->coordenadas);
?>
<div class="bairro-quarteirao-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
        'bairro' => $parentObject
	]); ?>

</div>
