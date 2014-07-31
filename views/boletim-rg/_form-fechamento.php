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
            echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
            ?>
        </div>
        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'categoria_id')->dropDownList(BairroCategoria::listData('nome')) ?>
        </div>
        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'bairro_quarteirao_id')->dropDownList(array()) ?>
        </div>
        
        <div class="col-xs-2 col-lg-offset-2 bairro-hide">
            <?= $form->field($model, 'folha')->textInput() ?>
        </div>
        
        <div class="col-xs-2 bairro-hide">
            <?= $form->field($model, 'data')->textInput(['class' => 'form-control input-datepicker'])->input('date') ?>
        </div>
    </div>
    <br />
    <div class="row bairro-hide">
        
        <table id="form-imoveis" class="table table-hover">
            <thread>
                <tr>
                    <th class="col-md-2">Tipo do Imóvel</th>
                    <th class="col-md-1">Lira</th>
                    <th class="col-md-1">Não Lira</th>
                </tr>
            </thread>
            <tbody>

                <?php
                $tipoImovel = ImovelTipo::find()->ativo()->all();
                if($tipoImovel) :
                    foreach($tipoImovel as $tipo) :
                ?>
                    <tr id="linha-<?= $tipo->id; ?>">
                        <td><?= $tipo->nome; ?></td>
                        <td><?= Html::input('number', 'BoletimRg[fechamentos][' . $tipo->id . '][lira]', (isset($model->fechamentos[$tipo->id]['lira']) ? $model->fechamentos[$tipo->id]['lira'] : 0), ['class' => 'form-control']) ?></td>
                        <td><?= Html::input('number', 'BoletimRg[fechamentos][' . $tipo->id . '][nao_lira]', (isset($model->fechamentos[$tipo->id]['nao_lira']) ? $model->fechamentos[$tipo->id]['nao_lira'] : 0), ['class' => 'form-control']) ?></td>
                    </tr>
                <?php 
                    endforeach;
                endif; 
                ?>
            </tbody>
        </table>
       <?= Html::error($model, 'fechamentos') ?>
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
    var quarteiraoID = null;
';

if(!$model->bairro_id)
    $script .= 'jQuery(".bairro-hide").hide();';
else
    $script .= 'bairroID = ' . $model->bairro_id . ';';

if($model->bairro_quarteirao_id)
    $script .= 'quarteiraoID = ' . $model->bairro_quarteirao_id . ';';

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
        jQuery.getJSON("' . Url::toRoute(['boletim-rg/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

            options = $("#boletimrg-bairro_quarteirao_id");
            options.append($("<option />").val("").text("Selecione..."));
            $.each(data, function(key, desc) {
                options.append($("<option />").val(key).text(desc));
            });

            jQuery("#boletimrg-categoria_id").parent().parent().show();
            jQuery("#boletimrg-bairro_quarteirao_id").parent().parent().show();
            
            if(quarteiraoID) 
                jQuery("#boletimrg-bairro_quarteirao_id").val(quarteiraoID);
        });
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

                jQuery.getJSON("' . Url::toRoute(['boletim-rg/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID, function(data) {

                    options = $("#boletimrg-bairro_quarteirao_id");
                    options.append($("<option />").val("").text("Selecione..."));
                    $.each(data, function(key, desc) {
                        options.append($("<option />").val(key).text(desc));
                    });
                    
                    jQuery("#boletimrg-categoria_id").parent().parent().show();
                    jQuery("#boletimrg-bairro_quarteirao_id").parent().parent().show();
                });
                
            });

        }
        
        jQuery("#boletimrg-bairro_quarteirao_id").change(function(){
            if($(this).val() != "") {
                jQuery(".bairro-hide").show();
            }
            else {
                jQuery(".bairro-hide").hide();
                jQuery("#boletimrg-categoria_id").parent().parent().show();
                jQuery("#boletimrg-bairro_quarteirao_id").parent().parent().show();
            }
        });

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