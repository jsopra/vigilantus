<?php
use app\models\Cliente;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Municipio;
use yii\widgets\MaskedInput;
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

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'ativo')->checkbox() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'nome_contato')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'email_contato')->textInput() ?>
            </div>
            <div class="col-xs-3">

                <?= Html::activeLabel($model,'telefone_contato'); ?>

                <?= MaskedInput::widget([
                    'model' => $model,
                    'attribute' => 'telefone_contato',
                    'mask' => '(99) [9]9999-9999'
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'departamento')->textInput() ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'cargo')->textInput() ?>
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
