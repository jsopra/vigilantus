<?php
use app\models\Cliente;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\Municipio;
?>

<div class="cliente-form">

	<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'municipio_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + Municipio::listData('nome'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
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
                array('/cliente/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Clientes')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
