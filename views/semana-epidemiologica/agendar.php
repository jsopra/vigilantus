<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\Bairro;
use app\models\EquipeAgente;
use app\models\SemanaEpidemiologica;
use app\models\VisitaStatus;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


$this->title = 'Agendar';

$this->params['breadcrumbs'][] = ['label' => 'Semanas Epidemiológicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visitas de Agentes', 'url' => ['agentes', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]];
$this->params['breadcrumbs'][] = ['label' => 'Visitas', 'url' => ['visitas', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semana-epidemiologica-agendar">

    <h1><?= Html::encode($this->title) ?> visitas</h1>

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
        	<div class="col-xs-4">
                <?php
                $semanas = SemanaEpidemiologica::find()->orderBy('nome')->all();
                echo $form->field($model, 'semana_epidemiologica_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + ArrayHelper::map($semanas, 'id', 'nome'),
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]
                );
                ?>
            </div>
        	<div class="col-xs-4">
                <?php
                $agentes = EquipeAgente::find()->orderBy('nome')->all();
                echo $form->field($model, 'agente_id')->widget(
                    Select2::classname(),
                    [
                        'data' => ['' => ''] + ArrayHelper::map($agentes, 'id', 'nome'),
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]
                );
                ?>
            </div>
            <div class="col-xs-4">
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
        </div>

        <div class="row bairro-hide">
            <div class="col-xs-12">
            	<?= $form->field($model, 'quarteiroes')->textInput(['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="form-group form-actions">
            <?php
            echo Html::submitButton('Cadastrar',
                ['class' => 'btn btn-flat success']
            );

            echo Html::a(
                'Cancelar',
                array('/semana-epidemiologica/visitas', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Voltar')
            );

            ?>
       </div>
    <?php ActiveForm::end(); ?>
</div>


<?php
$javascript = '
    var bairro = "' . $model->bairro_id . '";
    if (bairro == "") {
	   jQuery(".bairro-hide").hide();
    } else {
        jQuery(".bairro-hide").show();
        startSelect2(bairro);
        jQuery("#semanaepidemiologicavisitaagendamentoform-bairro_id").attr("disabled","disabled");
    }

	jQuery("#semanaepidemiologicavisitaagendamentoform-semana_epidemiologica_id").attr("disabled","disabled");
	jQuery("#semanaepidemiologicavisitaagendamentoform-agente_id").attr("disabled","disabled");

	jQuery("#semanaepidemiologicavisitaagendamentoform-bairro_id").change(function(){
        if($(this).val() != "") {
            jQuery(".bairro-hide").show();
            startSelect2($(this).val());
			jQuery("#semanaepidemiologicavisitaagendamentoform-bairro_id").attr("disabled","disabled");
        }
        else {
            jQuery(".bairro-hide").hide();
        }
    });

    function startSelect2(bairroID)
    {
        $("#semanaepidemiologicavisitaagendamentoform-quarteiroes").select2({
            multiple: true,
            placeholder: "Buscar quarteirão...",
            allowClear: true,
            ajax: {
                multiple: true,
                url: "' . Url::toRoute(['semana-epidemiologica/bairroQuarteiroes', 'bairro_id' => '']) . '" + bairroID,
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
        });
    }

    jQuery("form").submit(function(){
        jQuery("#semanaepidemiologicavisitaagendamentoform-bairro_id").removeAttr("disabled");
    });
';

$this->registerJs($javascript);
