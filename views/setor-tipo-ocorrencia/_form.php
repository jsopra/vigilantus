<?php
use app\models\Setor;
use app\models\OcorrenciaTipoProblema;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
?>

<div>
    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-5">
               <?php
                $usuarios = OcorrenciaTipoProblema::find()->associadoUnicamenteAoSetor($setor->id)->orderBy('nome')->all();
                echo $form->field($model, 'tipos_problemas_id')->widget(
                Select2::classname(),
                [
                    'data' => ['' => ''] + ArrayHelper::map($usuarios, 'id', 'nome'),
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]
            ); ?>
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
                array('/setor-tipo-ocorrencia/index', 'parentID' => $setor->id),
                array('class'=>'link','rel'=>'tooltip', 'data-role' => 'cancel','data-title'=>'Ir à lista de Setores')
            );

            ?>

       </div>

    <?php ActiveForm::end(); ?>
</div>
