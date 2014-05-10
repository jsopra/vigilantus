<?php
use app\models\BairroQuarteirao;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\ImovelTipo;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
?>

<div class="foco-transmissor-form">

	<?php $form = ActiveForm::begin(); ?>
    
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'laboratorio')->textInput(['maxlength' => 256]) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'tecnico')->textInput(['maxlength' => 256]) ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xs-3">
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
            <div class="col-xs-3">
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
        </div>
    
        <div class="row">
            <div class="col-xs-9">
                <?php
                echo $form->field($model, 'imovel_id')->widget(Select2::classname(), [
                    'options' => ['placeholder' => 'Buscar por um imóvel...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'ajax' => [
                            'url' => Url::toRoute(['foco-transmissor/imoveis']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(term,page) { return {q:term}; }'),
                            'results' => new JsExpression('function(data,page) { return {results : $.map(data, function (item) { return { text:item.name, slug:item.name,   id:item.id } } ) }; }'),
                        ],
                        'initSelection' => new JsExpression('function (item, callback) {
                            var id = ' . (!$model->getIsNewRecord() ? $model->imovel_id : 'null') . ';
                            var text = "' . (!$model->getIsNewRecord() && $model->imovel ? $model->imovel->getEnderecoCompleto() : 'null') . '";
                            var data = { id: id, text: text, slug: text };
                            callback(data);
                        }'),
                    ],
                ]);
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
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'quantidade_forma_aquatica')->input('number') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'quantidade_forma_adulta')->input('number') ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'quantidade_ovos')->input('number') ?>
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
                array('/foco-transmissor/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Foco Transmissors')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
