<?php
use app\models\Municipio;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\BairroCategoria;
use app\models\ImovelTipo;
use app\models\ImovelCondicao;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
            <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione..']) ?>
        </div>
        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'categoria_id')->dropDownList(BairroCategoria::listData('nome')) ?>
        </div>
        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'bairro_quarteirao_id')->textInput() ?>
        </div>
        <div class="col-xs-1 bairro-hide">
            <?= $form->field($model, 'seq')->textInput() ?>
        </div>
        
        <div class="col-xs-2 col-lg-offset-1 bairro-hide">
            <?= $form->field($model, 'folha')->textInput() ?>
        </div>
        <div class="col-xs-1 bairro-hide">
            <?= $form->field($model, 'mes')->textInput() ?>
        </div>
        <div class="col-xs-1 bairro-hide">
            <?= $form->field($model, 'ano')->textInput() ?>
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
                    <th class="col-md-2">Condição de Imóvel</th>
                    <th class="col-md-1">Existe foco?</th>
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
                        <td><?= Html::dropDownList('BoletimRg[imoveis][' . $qtdeImoveis . '][imovel_tipo]', $imovel['imovel_tipo'], (['' => 'Selecione...'] + ImovelTipo::listData('nome')), ['class' => 'form-control']) ?></td>
                        <td><?= Html::dropDownList('BoletimRg[imoveis][' . $qtdeImoveis . '][imovel_condicao]', $imovel['imovel_condicao'], (['' => 'Selecione...'] + ImovelCondicao::listData('nome')), ['class' => 'form-control']) ?></td>
                        <td style="text-align: center;"><?= Html::checkbox('BoletimRg[imoveis][' . $qtdeImoveis . '][existe_foco]', (isset($imovel['existe_foco']) ? $imovel['existe_foco'] : false)) ?></td>
                        <td style="text-align: center;" class="add-row-button"><a href="javascript:void(0);" onclick="javascript:removeImovel('linha-<?= $qtdeImoveis; ?>');"><i class="icon-trash"></i></a></td>
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
                    <td><?= Html::dropDownList('BoletimRg[imoveis][exemplo][imovel_tipo]', null, (['' => 'Selecione...'] + ImovelTipo::listData('nome')), ['class' => 'form-control', 'id' => 'selecaoTipoImovel']) ?></td>
                    <td><?= Html::dropDownList('BoletimRg[imoveis][exemplo][imovel_condicao]', null, (['' => 'Selecione...'] + ImovelCondicao::listData('nome')), ['class' => 'form-control', 'id' => 'selecaoCondicaoImovel']) ?></td>
                    <td style="text-align: center;"><?= Html::checkbox('BoletimRg[imoveis][exemplo][existe_foco]', false, ['id' => 'selecaoExisteFoco']) ?></td>
                    <td style="text-align: center;" class="add-row-button"><a href="javascript:void(0);" onclick="javascript:adicionaImovel();"><i class="icon-plus-sign"></i></a></td>
                </tr>
            </tbody>
        </table>
       <?= Html::error($model, 'imoveis') ?>
    </div>

    <div class="form-group vigilantus-form">
        <?php
        echo Html::submitButton(
            $model->isNewRecord ? 'Cadastrar' : 'Atualizar',
            ['class' => $model->isNewRecord ? 'btn btn-flat success' : 'btn btn-flat primary']
        );

        echo Html::a(
            'Cancelar',
            array('/bairro-quarteirao/index'),
            array('class'=>'link','rel'=>'tooltip','data-title'=>'Ir à lista de boletins cadastrados')
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

if(!$model->bairro_id)
    $script .= 'jQuery(".bairro-hide").hide();';
else
    $script .= 'bairroID = ' . $model->bairro_id . ';';

if(!$model->isNewRecord) {
    $script .= '
        jQuery("#dadosPrincipais").find("input").attr("readonly","readonly");
        jQuery("#dadosPrincipais").find("select").attr("readonly","readonly");
    ';
}

$script .= '

        jQuery("input#selecaoNumero").numeric();
        jQuery("input#boletimrg-bairro_quarteirao_id").numeric();
        jQuery("input#boletimrg-folha").numeric();
        jQuery("input#boletimrg-mes").numeric();
        jQuery("input#boletimrg-ano").numeric();
        
        if(bairroID) {
            jQuery("#selecaoRua").typeahead([{
                name: "BoletimRg[imoveis][exemplo][rua]",
                remote: "' . Html::url(['ficha-rg/bairroRuas']) . '?onlyName=true&bairro_id=" + bairroID + "&q=%QUERY"
            }]);
        }
        
        jQuery("#boletimrg-bairro_id").change(function() {
        
            if(jQuery(this).val() == "") {
                jQuery(".bairro-hide").hide();
                bairroID = null;
            }
            else {
            
                bairroID = jQuery(this).val();

                jQuery(this).attr("disabled","disabled");

                jQuery.getJSON("' . Html::url(['ficha-rg/bairroCategoria']) . '?bairro_id=" + bairroID, function(data) {

                    $("#boletimrg-categoria_id").val(data.id);
                    $("#boletimrg-categoria_id").attr("disabled","disabled");
                    
                    jQuery(".bairro-hide").show();
                });
 
             }
            
            jQuery("#selecaoRua").typeahead([{
                name: "BoletimRg[imoveis][exemplo][rua]",
                remote: "' . Html::url(['ficha-rg/bairroRuas']) . '?onlyName=true&bairro_id=" + bairroID + "&q=%QUERY"
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
        
        if(jQuery('#selecaoCondicaoImovel').val() == '') {
            jQuery('#selecaoCondicaoImovel').focus();
            return false;
        }
        
        var oldTr = jQuery('tr.add-row');
        
        var newTr = oldTr.clone();
        
        newTr.find('select#selecaoTipoImovel').val(oldTr.find('select#selecaoTipoImovel').val()); 
        newTr.find('select#selecaoCondicaoImovel').val(oldTr.find('select#selecaoCondicaoImovel').val()); 
        
        oldTr.find('input#selecaoRua').val(''); 
        oldTr.find('input#selecaoNumero').val(''); 
        oldTr.find('input#selecaoSeq').val(''); 
        oldTr.find('input#selecaoComplemento').val(''); 
        oldTr.find('select#selecaoTipoImovel').val(''); 
        oldTr.find('select#selecaoCondicaoImovel').val(''); 
        oldTr.find('input#selecaoExisteFoco').attr('checked', false);
       
        newTr.find('td.add-row-button').html('<a href="javascript:void(0);" onclick="javascript:removeImovel(\'linha-' + itemAtual + '\');"><i class="icon-trash"></i></a>');
        
        newTr.find('input#selecaoRua').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][rua]'); 
        newTr.find('input#selecaoNumero').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][numero]'); 
        newTr.find('input#selecaoSeq').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][seq]'); 
        newTr.find('input#selecaoComplemento').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][complemento]'); 
        newTr.find('select#selecaoTipoImovel').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][imovel_tipo]'); 
        newTr.find('select#selecaoCondicaoImovel').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][imovel_condicao]'); 
        newTr.find('input#selecaoExisteFoco').attr('name', 'BoletimRg[imoveis][' + itemAtual + '][existe_foco]'); 
        
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
