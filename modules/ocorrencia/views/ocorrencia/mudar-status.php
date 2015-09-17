<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OcorrenciaStatus;

$this->title = 'Alterar status de Ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar status';

if($model->email) {
    Yii::$app->session->setFlash('warning', 'ATENÇÃO: O denunciante preencheu o email ao fazer a ocorrência, assim ele receberá uma mensagem informando sobre a atualização do status.');
}
?>
<div class="ocorrencia-update">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin([]); ?>

	<div class="row">
        <div class="col-xs-4">
            <?php
            echo $form->field($model, 'status')->dropDownList(OcorrenciaStatus::getStatusPossiveis($model->status), ['prompt' => 'Selecione..']);
            ?>
        </div>
    </div>

	<div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                'Cadastrar',
                ['class' => 'btn btn-flat success']
            );

            echo Html::a(
                'Cancelar',
                Yii::$app->request->referrer,
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Ocorrências')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>
</div>
