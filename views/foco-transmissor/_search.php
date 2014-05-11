<?php
use yii\helpers\Html;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
?>

<div class="foco-transmissor-search well well-sm">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <div class="row">
            
            <div class="col-xs-3">
                <?= $form->field($model, 'bairro_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + DepositoTipo::listData('descricao'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
            </div>
        </div>
    
        <div class="row">
            
            <div class="col-xs-2">
                <?= $form->field($model, 'tipo_deposito_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + DepositoTipo::listData('descricao'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
            </div>
            
            <div class="col-xs-2">
                <?= $form->field($model, 'especie_transmissor_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + EspecieTransmissor::listData('nome'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
            </div>
            
            <div class="col-xs-2">
                <?= $form->field($model, 'foco_ativo')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + DepositoTipo::listData('descricao'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
            </div>
            
            <div class="col-xs-2">
                <?= $form->field($model, 'imovel_lira')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + DepositoTipo::listData('descricao'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                );
                ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'data_entrada')->input('date') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'data_exame')->input('date') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'data_coleta')->input('date') ?>
            </div>
        </div>
    
    
    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-flat success search-btn']); ?>
        <?= Html::resetButton('Limpar', ['class' => 'btn btn-flat primary search-btn']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
