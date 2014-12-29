<?php
use app\models\Municipio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\models\MunicipioHelper;
?>

<div class="municipio-form">

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-xs-4">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
            <div class="col-xs-2">
                <?= $form->field($model, 'sigla_estado')->textInput(['maxlength' => 2]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'coordenadas_area')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'file')->fileInput() ?>

                <?php if($model->brasao) : ?>
                    <?= MunicipioHelper::getBrasaoAsImageTag($model, 'small'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group form-actions">
			<?php 
            echo Html::submitButton(
                $model->isNewRecord ? 'Cadastrar' : 'Atualizar', 
                ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
            );
		
            echo Html::a(
                'Cancelar',
                array('/municipio/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Municipios')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
