<?php
use app\models\Denuncia;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
?>

<div class="denuncia-form">

	<?php $form = ActiveForm::begin(); ?>

        <?= Html::a(
            '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar às denúncias',
            Yii::$app->urlManager->createUrl('denuncia/index'),
            [
                'class' => 'btn btn-link',
            ]
        );
        ?>

        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Objeto da denúncia',
                    'content' => $this->render('_objetoDenuncia', ['form' => $form, 'model' => $model]),
                    'active' => true
                ],
                [
                    'label' => 'Dados do denunciante',
                    'content' => $this->render('_dadosDenunciante', ['form' => $form, 'model' => $model]),
                    'active' => false
                ],
            ]
        ]);
        ?>
    
        <div class="form-group form-actions">
            <?php 
            echo Html::submitButton(
                'Aprovar', 
                ['class' => 'btn btn-flat primary']
            );

            echo Html::a(
                'Reprovar', 
                array('/denuncia/reprovar', 'id' => $model->id),
                ['class' => 'btn btn-flat inverse']
            );
        
            echo Html::a(
                'Cancelar',
                array('/denuncia/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Denuncias')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>


<?php
$view = Yii::$app->getView();
$script = '
    jQuery(document).ready(function(){

        var bairroID = ' . $model->bairro_id . ';
        var quarteiraoID = null;
';

if($model->bairro_quarteirao_id) {
    $script .= 'quarteiraoID = ' . $model->bairro_quarteirao_id . ';';
}

if($model->imovel_id) {
    $script .= 'startSelect2();';
}

$script .= '

    jQuery("#denuncia-bairro_quarteirao_id").change(function(){
        if($(this).val() != "") {
            jQuery(".bairro-hide").show();
            startSelect2();
        }
        else {
            jQuery(".bairro-hide").hide();
        }
    });

    function startSelect2() {

        $("#denuncia-imovel_id").select2({
            placeholder: "Buscar por um imóvel...",
            allowClear: true,
            ajax: { 
                url: "' . Url::toRoute(['denuncia/imoveis', 'bairro_id' => '']) . '" + bairroID,
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
    
    jQuery("#denuncia-bairro_quarteirao_id").change();
});
';
$view->registerJs($script);
?>