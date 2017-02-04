<?php
use app\models\Ocorrencia;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Bairro;
use yii\helpers\ArrayHelper;
use app\models\BairroQuarteirao;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
?>

<div class="ocorrencia-form">

	<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

        <p style="color: #797979;"><strong>Controle interno</strong></p>

        <div class="row" id="dadosPrincipais">
            <div class="col-xs-4">
                <?= $form->field($model, 'numero_controle'); ?>
            </div>
            <div class="col-xs-4">
                <?= $form->field($model, 'data_criacao')->input('date', ['class' => 'form-control input-datepicker']) ?>
            </div>
        </div>

        <hr />

        <p style="color: #797979;"><strong>Objeto da ocorrência</strong></p>

        <div class="row" id="dadosPrincipais">
            <div class="col-xs-4">
                <?php
                $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
                echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
                ?>
            </div>
            <div class="col-xs-4 bairro-hide">
                <?php
                $quarteiroes = BairroQuarteirao::find()->doBairro($model->bairro_id)->orderBy('numero_quarteirao')->all();
                echo $form->field($model, 'bairro_quarteirao_id')->dropDownList(ArrayHelper::map($quarteiroes, 'id', 'numero_quarteirao'));
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'endereco') ?>
            </div>
        </div>

        <div class="row bairro-hide">
            <div class="col-xs-8">
                <?= $form->field($model, 'imovel_id')->widget(
                    Select2::classname(),
                    [
                        'options' => ['placeholder' => 'Buscar por um imóvel...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'ajax' => [
                                'url' => "' . Url::toRoute(['ocorrencia/imoveis', 'bairro_id' => $model->bairro_id]) . '",
                                'dataType' => 'json',
                                'data' => new JsExpression('function(term,page) { return {q:term}; }'),
                                'results' => new JsExpression('function (data, page) {
                                    return {
                                        results : $.map(data, function (item) {
                                            return {
                                                text:item.name, slug:item.name, id:item.id
                                            }
                                        })
                                    };
                                }'),
                            ],
                        ],
                    ]
                );
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <?php
                echo $form->field($model, 'tipo_imovel')->dropDownList(\app\models\OcorrenciaTipoImovel::getDescricoes(), ['prompt' => 'Selecione..']);
                ?>
            </div>

            <div class="col-xs-4">
                <?php
                $tipos = \app\models\OcorrenciaTipoProblema::find()->ativos()->orderBy('nome')->all();
                echo $form->field($model, 'ocorrencia_tipo_problema_id')->dropDownList(['0' => 'Selecione'] + ArrayHelper::map($tipos, 'id', 'nome') + ['' => 'Outros']);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <?= $form->field($model, 'pontos_referencia') ?>
            </div>
            <div class="col-xs-4" id="bloco-outro-tipo-problema">
                <?= $form->field($model, 'descricao_outro_tipo_problema') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <?= $form->field($model, 'tipo_registro')->dropDownList(Ocorrencia::getTiposRegistros()) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'mensagem')->textArea() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
        </div>

        <hr />

        <p style="color: #797979;"><strong>Dados do denunciante</strong></p>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <?= $form->field($model, 'email')->textInput() ?>
            </div>

            <div class="col-xs-4">
                <?= Html::activeLabel($model, 'telefone') ?>
                <?php
                echo MaskedInput::widget([
                    'model' => $model,
                    'name' => 'telefone',
                    'mask' => '(99) 9999-9999',
                ]);
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

if($model->imovel_id) {
    $script .= 'startSelect2();';
}

$script .= '

    jQuery("#ocorrencia-bairro_quarteirao_id").change(function(){
        if($(this).val() != "") {
            jQuery(".bairro-hide").show();

            startSelect2();
        }
        else {
            jQuery(".bairro-hide").hide();
        }
    });

    if(bairroID) {

        jQuery.getJSON("' . Url::toRoute(['ocorrencia/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

            options = $("#ocorrencia-bairro_quarteirao_id");
            options.append($("<option />").val("").text("Selecione..."));
            $.each(data, function(key, desc) {
                options.append($("<option />").val(key).text(desc));
            });

            jQuery("#ocorrencia-bairro_quarteirao_id").parent().parent().show();

            if(quarteiraoID) {
                jQuery("#ocorrencia-bairro_quarteirao_id").val(quarteiraoID);
            }
        });
    }

    jQuery("#ocorrencia-bairro_id").change(function() {

        if(jQuery(this).val() == "") {
            jQuery(".bairro-hide").hide();
            bairroID = null;
        }
        else {

            bairroID = jQuery(this).val();

            jQuery(this).attr("disabled","disabled");

            jQuery.getJSON("' . Url::toRoute(['ocorrencia/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

                $("#ocorrencia-bairro_quarteirao_id").val("");

                options = $("#ocorrencia-bairro_quarteirao_id");
                options.append($("<option />").val("").text("Selecione..."));
                $.each(data, function(key, desc) {
                    options.append($("<option />").val(key).text(desc));
                });

                jQuery("#ocorrencia-bairro_quarteirao_id").parent().parent().show();
            });

        }
    });

    jQuery("form").submit(function(){
';


if($model->isNewRecord) {
    $script .= '
        jQuery("#ocorrencia-bairro_id").removeAttr("disabled");

        jQuery("#ocorrencia-bairro_id").attr("readonly","readonly");
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

    function startSelect2() {

        $("#ocorrencia-imovel_id").select2({
            placeholder: "Buscar por um imóvel...",
            allowClear: true,
            ajax: {
                url: "' . Url::toRoute(['ocorrencia/imoveis', 'bairro_id' => '']) . '" + bairroID,
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
        });
    }

    jQuery("#ocorrencia-bairro_quarteirao_id").change();
});
';
$view->registerJs($script);

$this->registerJs('
$(document).ready(function(){
    var checarTipoProblema = function() {
        if ($("#ocorrencia-ocorrencia_tipo_problema_id").val()) {
            $("#bloco-outro-tipo-problema").hide();
        } else {
            $("#bloco-outro-tipo-problema").show();
        }
    };
    $("#ocorrencia-ocorrencia_tipo_problema_id").change(checarTipoProblema);
    checarTipoProblema();
});');
