<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DenunciaStatus;

$this->title = 'Alterar status de Denuncia #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar status';
?>
<div class="denuncia-update">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin([]); ?>

	<div class="row">
            <div class="col-xs-4">
                <?php
                echo $form->field($model, 'status')->dropDownList(DenunciaStatus::getStatusPossiveis($model->status), ['prompt' => 'Selecione..']);
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
                array('/denuncia/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Denúncias')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>
</div>
