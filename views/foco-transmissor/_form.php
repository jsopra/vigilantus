<?php
use app\models\Bairro;
use app\models\BairroCategoria;
use app\models\BairroQuarteirao;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\ImovelTipo;
use app\helpers\models\ImovelHelper;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
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
    
        <div class="row" id="dadosPrincipais">
            <div class="col-xs-2">
                <?php
                $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
                echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
                ?>
            </div>
            
            <div class="col-xs-2 bairro-hide">
                <?= $form->field($model, 'categoria_id')->dropDownList(BairroCategoria::listData('nome')) ?>
            </div>
            
            <div class="col-xs-2 bairro-hide">
                <?= $form->field($model, 'bairro_quarteirao_id')->dropDownList(array()) ?>
            </div>
        </div>
    
        <div class="row bairro-hide">
            <div class="col-xs-9">
                <?= $form->field($model, 'imovel_id')->textInput(['class' => 'form-control']) ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xs-3">
                <?= $form->field($model, 'data_entrada')->input('date', ['class' => 'form-control input-datepicker']) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'data_exame')->input('date', ['class' => 'form-control input-datepicker']) ?>
            </div>
            <div class="col-xs-3">
                <?= $form->field($model, 'data_coleta')->input('date', ['class' => 'form-control input-datepicker']) ?>
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

<?php
$view = Yii::$app->getView();
$script = '
    jQuery(document).ready(function(){

    var bairroID = null;
    var quarteiraoID = null;
';

if(!$model->bairro_id) {
    $script .= 'jQuery(".bairro-hide").hide();';
}
else {
    $script .= 'bairroID = ' . $model->bairro_id . ';';
}

if($model->bairro_quarteirao_id) {
    $script .= 'quarteiraoID = ' . $model->bairro_quarteirao_id . ';';
}

if(!$model->isNewRecord) {
    $script .= '
        jQuery("#dadosPrincipais").find("input").attr("readonly","readonly");
        jQuery("#dadosPrincipais").find("select").attr("readonly","readonly");
    ';
}

if($model->imovel_id) {
    $script .= 'startSelect2();';
}

$script .= '

    if(bairroID) {

        jQuery.getJSON("' . Url::toRoute(['foco-transmissor/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

            options = $("#focotransmissor-bairro_quarteirao_id");
            options.append($("<option />").val("").text("Selecione..."));
            $.each(data, function(key, desc) {
                options.append($("<option />").val(key).text(desc));
            });

            jQuery("#focotransmissor-categoria_id").parent().parent().show();
            jQuery("#focotransmissor-bairro_quarteirao_id").parent().parent().show();
            
            if(quarteiraoID) 
                jQuery("#focotransmissor-bairro_quarteirao_id").val(quarteiraoID);
        });
    }

    jQuery("#focotransmissor-bairro_id").change(function() {

        if(jQuery(this).val() == "") {
            jQuery(".bairro-hide").hide();
            bairroID = null;
        }
        else {

            bairroID = jQuery(this).val();

            jQuery(this).attr("disabled","disabled");

            jQuery.getJSON("' . Url::toRoute(['foco-transmissor/bairroCategoria', 'bairro_id' => '']) . '" + bairroID, function(data) {

                $("#focotransmissor-categoria_id").val(data.id);
                $("#focotransmissor-categoria_id").attr("disabled","disabled");

                jQuery.getJSON("' . Url::toRoute(['foco-transmissor/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

                    options = $("#focotransmissor-bairro_quarteirao_id");
                    options.append($("<option />").val("").text("Selecione..."));
                    $.each(data, function(key, desc) {
                        options.append($("<option />").val(key).text(desc));
                    });
                    
                    jQuery("#focotransmissor-categoria_id").parent().parent().show();
                    jQuery("#focotransmissor-bairro_quarteirao_id").parent().parent().show();
                });
                
            });

        }
        
        jQuery("#focotransmissor-bairro_quarteirao_id").change(function(){
            if($(this).val() != "") {
                jQuery(".bairro-hide").show();
                
                startSelect2();
            }
            else {
                jQuery(".bairro-hide").hide();
                jQuery("#focotransmissor-categoria_id").parent().parent().show();
                jQuery("#focotransmissor-bairro_quarteirao_id").parent().parent().show();
            }
        });


        jQuery("form").submit(function(){
';
  

if($model->isNewRecord) {
    $script .= '
        jQuery("#focotransmissor-bairro_id").removeAttr("disabled");
        jQuery("#focotransmissor-categoria_id").removeAttr("disabled");

        jQuery("#focotransmissor-bairro_id").attr("readonly","readonly");
        jQuery("#focotransmissor-categoria_id").attr("readonly","readonly");
    ';
}
else {
    $script .= '
        jQuery("#dadosPrincipais").find("input").removeAttr("disabled");
        jQuery("#dadosPrincipais").find("select").removeAttr("disabled");

        jQuery("#dadosPrincipais").find("input").attr("readonly","readonly");
        jQuery("#dadosPrincipais").find("select").attr("readonly","readonly");
    ';
}

$script .= '
        });
    });

    function startSelect2() {

        $("#focotransmissor-imovel_id").select2({
            placeholder: "Buscar por um imóvel...",
            allowClear: true,
            ajax: { 
                url: "' . Url::toRoute(['foco-transmissor/imoveis', 'bairro_id' => '']) . '" + bairroID,
                dataType: "json",
                data: function (term, page) {
                    return {
                        q: term,
                    };
                },
                results: function (data, page) { 
                    return {
                        results : $.map(data, function (item) { 
                            return { 
                                text:item.name, slug:item.name, id:item.id 
                            } 
                        }) 
                    };
                }
            },
            initSelection: function(element, callback) {
                var id = ' . (!$model->getIsNewRecord() && $model->imovel_id ? $model->imovel_id : 'null') . ';
                var text = "' . (!$model->getIsNewRecord() && $model->imovel ? ImovelHelper::getEnderecoCompleto($model->imovel) : 'null') . '";
                var data = { id: id, text: text, slug: text };
                callback(data);
            }
        });
    }
    
});
';
$view->registerJs($script);
?>
