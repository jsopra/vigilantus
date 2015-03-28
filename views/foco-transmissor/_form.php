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
                        'data' => ['' => ''] + DepositoTipo::listData('descricao_sigla'),
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
                echo $form->field($model, 'bairro_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + ArrayHelper::map($bairros, 'id', 'nome'),
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]
                );
                ?>
            </div>

            <div class="col-xs-2 bairro-hide">
                <?= $form->field($model, 'categoria_id')->dropDownList(BairroCategoria::listData('nome')) ?>
            </div>

            <div class="col-xs-2 bairro-hide">
                <?= $form->field($model, 'bairro_quarteirao_id')->textInput(['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="row bairro-hide">
            <div class="col-xs-6">
                <?= $form->field($model, 'imovel_id')->textInput(['class' => 'form-control']) ?>
            </div>
            <div class="col-xs-3 tipo_imovel">
                <?= $form->field($model, 'planilha_imovel_tipo_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + ImovelTipo::listData('descricao_sigla'),
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
    var novoEndereco = false;

    jQuery(document).ready(function(){
        var bairroID = null;

        $(".tipo_imovel").hide();
';

if(!$model->bairro_id) {
    $script .= 'jQuery(".bairro-hide").hide();';
} else {
    $script .= 'bairroID = ' . $model->bairro_id . ';';
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

if($model->planilha_imovel_tipo_id) {
    $script .= 'startSelect2();';
    $script .= '$(".tipo_imovel").show();';
}

$script .= '

    if(bairroID) {

        startSelect2Quarteirao(bairroID);

        $("#focotransmissor-bairro_id").attr("disabled","disabled");
    }

    jQuery("#focotransmissor-bairro_id").change(function() {

        $(".tipo_imovel").hide();

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

                startSelect2Quarteirao(bairroID);
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

    var lastResults = [];

    function startSelect2() {

        $("#focotransmissor-imovel_id").select2({
            multiple: false,
            placeholder: "Buscar por um imóvel...",
            allowClear: true,
            ajax: {
                multiple: false,
                url: "' . Url::toRoute(['foco-transmissor/imoveis', 'bairro_id' => '']) . '" + bairroID,
                dataType: "json",
                data: function (term, page) {
                    return {
                        q: term,
                    };
                },
                results: function (data, page) {

                    lastResults = data;

                    return {
                        results : $.map(lastResults, function (item) {
                            return {
                                text:item.name, slug:item.name, id:item.id
                            }
                        })
                    };
                }
            },
            createSearchChoice: function (term) {
                $(".tipo_imovel").show();
                novoEndereco = true;
                var text = term + (lastResults.some(function(r) { return r.text == term }) ? "" : " (novo)");
                return { id: term, text: text };
            },
            initSelection: function(element, callback) {
                var id = ' . (!$model->getIsNewRecord() && $model->imovel_id ? $model->imovel_id : 'null') . ';
                var text = "' . (!$model->getIsNewRecord() ? ($model->imovel_id ? ImovelHelper::getEnderecoCompleto($model->imovel) : ($model->planilha_endereco ? $model->planilha_endereco : 'null')) : 'null') . '";
                var data = { id: id, text: text, slug: text };
                callback(data);
            }
        });
    }

    function startSelect2Quarteirao(bairroID)
    {
        jQuery("#focotransmissor-categoria_id").parent().parent().show();
        jQuery("#focotransmissor-bairro_quarteirao_id").parent().parent().show();

        $("#focotransmissor-bairro_quarteirao_id").select2({
            multiple: false,
            placeholder: "Buscar por um quarteirão...",
            allowClear: false,
            ajax: {
                multiple: false,
                url: "' . Url::toRoute(['foco-transmissor/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID,
                dataType: "json",
                data: function (term, page) {
                    return {
                        q: term,
                    };
                },
                results: function (data, page) {
                    return {
                        results : $.map(data, function (name, id) {
                            return {
                                text:name, slug:name, id:id
                            }
                        })
                    };
                }
            },
            initSelection: function(element, callback) {
                var id = ' . (!$model->getIsNewRecord() && $model->bairro_quarteirao_id ? $model->bairro_quarteirao_id  : 'null') . ';
                var text = "' . (!$model->getIsNewRecord() && $model->bairroQuarteirao ? $model->bairroQuarteirao->numero_quarteirao : 'null') . '";
                var data = { id: id, text: text, slug: text };
                callback(data);
            }
        });
    }

});
';
$view->registerJs($script);

?>
