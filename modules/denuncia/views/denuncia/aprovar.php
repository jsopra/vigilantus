<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Denuncia $model
 */

$this->title = 'Aprovar Denuncia #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Aprovar';

if($model->email) {
    Yii::$app->session->setFlash('warning', 'ATENÇÃO: O denunciante preencheu o email ao fazer a denúncia, assim ele receberá uma mensagem informando sobre a atualização do status.');
}
?>
<div class="denuncia-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('aprovar/_form', ['model' => $model]); ?>
</div>
