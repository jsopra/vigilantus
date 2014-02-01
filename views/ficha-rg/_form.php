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

	<div class="row">
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
        <table class="table table-hover">
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
                <tr class="add-row">
                    <td><?= Html::textInput('BoletimRg[imoveis][0][rua]', null, ['class' => 'form-control', 'id' => 'selecaoRua']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][0][numero]', null, ['class' => 'form-control']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][0][seq]', null, ['class' => 'form-control']) ?></td>
                    <td><?= Html::textInput('BoletimRg[imoveis][0][complemento]', null, ['class' => 'form-control']) ?></td>
                    <td><?= Html::dropDownList('BoletimRg[imoveis][0][imovel_tipo]', null, (['' => 'Selecione...'] + ImovelTipo::listData('nome')), ['class' => 'form-control']) ?></td>
                    <td><?= Html::dropDownList('BoletimRg[imoveis][0][imovel_condicao]', null, (['' => 'Selecione...'] + ImovelCondicao::listData('nome')), ['class' => 'form-control']) ?></td>
                    <td style="text-align: center;"><?= Html::checkbox('BoletimRg[imoveis][0][existe_foco]', false) ?></td>
                    <td style="text-align: center;"><a href="javascript:void(0);" onclick="javascript:adicionaImovel();"><i class="icon-plus-sign"></i></a></td>
                </tr>
            </tbody>
        </table>
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

        jQuery(".bairro-hide").hide();
        
        jQuery("#boletimrg-bairro_id").change(function() {
        
            if(jQuery(this).val() == "") {
                jQuery(".bairro-hide").hide();
            }
            else {
            
                var bairroID = jQuery(this).val();

                jQuery(this).attr("disabled","disabled");

                jQuery.getJSON("' . Html::url(['ficha-rg/bairroCategoria']) . '?bairro_id=" + bairroID, function(data) {

                    $("#boletimrg-categoria_id").val(data.id);
                    $("#boletimrg-categoria_id").attr("disabled","disabled");
                    
                    jQuery(".bairro-hide").show();
                });
 
                jQuery("#selecaoRua").typeahead([{
                    remote: "' . Html::url(['ficha-rg/bairroRuas']) . '?onlyName=true&bairro_id=" + bairroID
                }]);

            }
        });
    });
    
    function adicionaImovel() {
        alert("asd");
    }
';
$view->registerJs($script);
?>