<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Setor;
use yii\helpers\ArrayHelper;

$this->title = 'Alterar setor da ocorrência #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar setor';

if ($model->email) {
    Yii::$app->session->setFlash(
        'warning',
        'ATENÇÃO: O denunciante preencheu o email ao fazer a ocorrência, assim ele receberá uma mensagem informando sobre a atualização do setor.'
    );
}
?>
<div class="ocorrencia-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php if ($model->setor) : ?>
		<h4><strong>Setor atual da denúncia</strong>: <?= $model->setor->nome; ?></h4>
		<br />
	<?php endif; ?>

	<?php $form = ActiveForm::begin([]); ?>

	<div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'setor_id')
                ->dropDownList(ArrayHelper::map(Setor::find()->all(), 'id', 'nome'), ['prompt' => 'Selecione...']) ?>
        </div>
    </div>

	<div class="form-group form-actions">
			<?php
            echo Html::submitButton(
                'Alterar',
                ['class' => 'btn btn-flat success']
            );

            echo Html::a(
                'Cancelar',
                ['index'],
                [
                    'class' => 'link',
                    'rel' => 'tooltip',
                    'data-role' => 'cancel',
                    'data-title'=>'Ir à lista de Ocorrências'
                ]
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>
</div>
