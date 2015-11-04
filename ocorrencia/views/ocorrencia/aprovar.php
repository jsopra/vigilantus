<?php

use yii\helpers\Html;

$this->title = 'Aprovar Ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Aprovar';

if($model->email) {
    Yii::$app->session->setFlash('warning', 'ATENÇÃO: O denunciante preencheu o email ao fazer a ocorrência, assim ele receberá uma mensagem informando sobre a atualização do status.');
}
?>

<div class="ocorrencia-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('aprovar/_form', ['model' => $model]); ?>
</div>
