<?php
use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\BairroCategoria;
use app\models\ImovelTipo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\models\BairroTipo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bairro-tipo-form">

	<?php $form = ActiveForm::begin(); ?>

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

        <div class="col-xs-2 col-lg-offset-2 bairro-hide">
            <?= $form->field($model, 'folha')->textInput() ?>
        </div>

        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'data')->input('date', ['class' => 'form-control input-datepicker']) ?>
        </div>
    </div>
    <br />
    <div class="row bairro-hide">

        <table id="form-imoveis" class="table table-hover">
            <thread>
                <tr>
                    <th class="col-md-2">Rua/Logradouro</th>
                    <th class="col-md-1">Nº</th>
                    <th class="col-md-1">Seq</th>
                    <th class="col-md-2">Complemento</th>
                    <th class="col-md-2">Tipo de Imóvel</th>
                    <th class="col-md-1">Lira?</th>
                    <th class="col-md-1">&nbsp;</th>
                </tr>
            </thread>
            <tbody>

                <?php
                $qtdeImoveis = 0;
                if($model->imoveis) :
                    foreach($model->imoveis as $imovel) :
                ?>
                    <tr id="linha-<?= $qtdeImoveis; ?>">
                        <td><?= Html::textInput('BoletimRg[imoveis][' . $qtdeImoveis . '][rua]', $imovel['rua'], ['class' => 'form-control']) ?></td>
                        <td><?= Html::textInput('BoletimRg[imoveis][' . $qtdeImoveis . '][numero]', $imovel['numero'], ['class' => 'form-control']) ?></td>
                        <td><?= Html::textInput('BoletimRg[imoveis][' . $qtdeImoveis . '][seq]', $imovel['seq'], ['class' => 'form-control']) ?></td>
                        <td><?= Html::textInput('BoletimRg[imoveis][' . $qtdeImoveis . '][complemento]', $imovel['complemento'], ['class' => 'form-control']) ?></td>
                        <td><?= Html::dropDownList('BoletimRg[imoveis][' . $qtdeImoveis . '][imovel_tipo]', $imovel['imovel_tipo'], (['' => 'Selecione...'] + ImovelTipo::find()->ativo()->listData('nome')), ['class' => 'form-control']) ?></td>
                        <td style="text-align: center;"><?= Html::checkbox('BoletimRg[imoveis][' . $qtdeImoveis . '][imovel_lira]', (isset($imovel['imovel_lira']) ? $imovel['imovel_lira'] : false)) ?></td>
                        <td style="text-align: center;" class="add-row-button"><a href="javascript:void(0);" onclick="javascript:removeImovel('linha-<?= $qtdeImoveis; ?>');" title="Remover <?= $qtdeImoveis; ?>"><i class="icon-trash"></i></a></td>
                    </tr>
                <?php
                        $qtdeImoveis++;
                    endforeach;
                endif;
                ?>
                <tr class="add-row">
                    <td><?= Html::textInput('BoletimRg[imoveis][exemplo][rua]', null, ['class' => 'form-control', 'id' => 'selecaoRua']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][exemplo][numero]', null, ['class' => 'form-control', 'id' => 'selecaoNumero']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][exemplo][seq]', null, ['class' => 'form-control', 'id' => 'selecaoSeq']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][exemplo][complemento]', null, ['class' => 'form-control', 'id' => 'selecaoComplemento']) ?></td>
                    <td><?= Html::dropDownList('BoletimRg[imoveis][exemplo][imovel_tipo]', null, (['' => 'Selecione...'] + ImovelTipo::find()->ativo()->listData('nome')), ['class' => 'form-control', 'id' => 'selecaoTipoImovel']) ?></td>
                    <td style="text-align: center;"><?= Html::checkbox('BoletimRg[imoveis][exemplo][imovel_lira]', false, ['id' => 'selecaoImovelLira']) ?></td>
                    <td style="text-align: center;" class="add-row-button"><a href="javascript:void(0);" onclick="javascript:adicionaImovel();" title="Adicionar"><i class="icon-plus-sign"></i></a></td>
                </tr>
            </tbody>
        </table>
       <?= Html::error($model, 'imoveis') ?>
    </div>

    <div class="form-group form-actions">
        <?php
        echo Html::submitButton(
            $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
            ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
        );

        echo Html::a(
            'Cancelar',
            array('index'),
            array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de boletins cadastrados')
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

$script .= '

    jQuery("input#selecaoNumero").numeric();
    jQuery("input#selecaoSeq").numeric();

    jQuery("input#boletimrg-folha").numeric();
    jQuery("input#boletimrg-mes").numeric();
    jQuery("input#boletimrg-ano").numeric();

    if(bairroID) {

        jQuery("#selecaoRua").typeahead([{
            name: "BoletimRg[imoveis][exemplo][rua]",
            remote: "' . Url::toRoute(['boletim-rg/ruas']) . '?onlyName=true&q=%QUERY"
        }]);

        startSelect2(bairroID);

        $("#boletimrg-bairro_id").attr("disabled","disabled");
    }

    jQuery("#boletimrg-bairro_id").change(function() {

        if(jQuery(this).val() == "") {
            jQuery(".bairro-hide").hide();
            bairroID = null;
        }
        else {

            bairroID = jQuery(this).val();

            jQuery(this).attr("disabled","disabled");

            jQuery.getJSON("' . Url::toRoute(['boletim-rg/bairroCategoria', 'bairro_id' => '']) . '" + bairroID, function(data) {

                $("#boletimrg-categoria_id").val(data.id);
                $("#boletimrg-categoria_id").attr("disabled","disabled");

                startSelect2(bairroID);

            });

        }

        jQuery("#boletimrg-bairro_quarteirao_id").change(function(){

            $(this).attr("disabled","disabled");

            if($(this).val() != "") {
                jQuery(".bairro-hide").show();
            }
            else {
                jQuery(".bairro-hide").hide();
                jQuery("#boletimrg-categoria_id").parent().parent().show();
                jQuery("#boletimrg-bairro_quarteirao_id").parent().parent().show();
            }
        });

        jQuery("#selecaoRua").typeahead([{
            name: "BoletimRg[imoveis][exemplo][rua]",
            remote: "' . Url::toRoute(['boletim-rg/ruas', 'onlyName' => 'true']) . '&q=%QUERY"
        }]);

        jQuery("form").submit(function(){
';


if($model->isNewRecord) {
    $script .= '
        jQuery("#boletimrg-bairro_id").removeAttr("disabled");
        jQuery("#boletimrg-categoria_id").removeAttr("disabled");

        jQuery("#boletimrg-bairro_id").attr("readonly","readonly");
        jQuery("#boletimrg-categoria_id").attr("readonly","readonly");
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
    });

    function startSelect2(bairroID)
    {
        jQuery("#boletimrg-categoria_id").parent().parent().show();
        jQuery("#boletimrg-bairro_quarteirao_id").parent().parent().show();

        $("#boletimrg-bairro_quarteirao_id").select2({
            multiple: false,
            placeholder: "Buscar por um quarteirão...",
            allowClear: false,
            ajax: {
                multiple: false,
                url: "' . Url::toRoute(['boletim-rg/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID,
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
                var text = "' . (!$model->getIsNewRecord() && $model->quarteirao ? $model->quarteirao->numero_quarteirao : 'null') . '";
                var data = { id: id, text: text, slug: text };
                callback(data);
            }
        });
    }
';
$view->registerJs($script);
?>
<script>
    var itemAtual = <?= $qtdeImoveis > 0 ? $qtdeImoveis : '0'; ?>;

    function adicionaImovel() {

        if(jQuery('#selecaoRua').val() == '') {
            jQuery('#selecaoRua').focus();
            return false;
        }

        if(jQuery('#selecaoNumero').val() == '') {
            jQuery('#selecaoNumero').focus();
            return false;
        }

        if(jQuery('#selecaoTipoImovel').val() == '') {
            jQuery('#selecaoTipoImovel').focus();
            return false;
        }

        var oldTr = jQuery('tr.add-row');

        var newTr = oldTr.clone();

        newTr.find('select#selecaoTipoImovel').val(oldTr.find('select#selecaoTipoImovel').val());

        oldTr.find('input#selecaoRua').val('');
        oldTr.find('input#selecaoNumero').val('');
        oldTr.find('input#selecaoSeq').val('');
        oldTr.find('input#selecaoComplemento').val('');
        oldTr.find('select#selecaoTipoImovel').val('');
        oldTr.find('input#selecaoImovelLira').attr('checked', false);

        newTr.find('td.add-row-button').html('<a href="javascript:void(0);" onclick="javascript:removeImovel(\'linha-' + itemAtual + '\');"><i class="icon-trash"></i></a>');

        newTr.find('input#selecaoRua').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][rua]');
        newTr.find('input#selecaoNumero').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][numero]');
        newTr.find('input#selecaoSeq').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][seq]');
        newTr.find('input#selecaoComplemento').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][complemento]');
        newTr.find('select#selecaoTipoImovel').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][imovel_tipo]');
        newTr.find('input#selecaoImovelLira').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][imovel_lira]');

        newTr.find('input').removeAttr('id');
        newTr.find('select').removeAttr('id');

        newTr.removeClass('add-row');
        newTr.attr('id', 'linha-' + itemAtual);

        oldTr.before(newTr);

        itemAtual++;
    }

    function removeImovel(item) {
        $('#' + item).remove();
    }
</script>
<style>
    .add-row {
        background-color: #edf2f7;
    }
</style>
