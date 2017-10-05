<?php
use app\models\CasoDoenca;
use app\models\Doenca;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="caso-doenca-form">

	<?php $form = ActiveForm::begin(); ?>

        <div>
            <div class="row">
                <div class="col-xs-3">
                    <?php
                    $doencas = Doenca::find()->orderBy('nome')->all();
                    echo $form->field($model, 'doenca_id')->dropDownList(ArrayHelper::map($doencas, 'id', 'nome'), ['prompt' => 'Selecione..']); ?>
                </div>
            <div class="row">
                <div class="col-xs-4">
                    <?php
                $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
                echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']); ?>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'data_sintomas')->textInput() ?>
                </div>
                <div class="col-xs-4">
                    <?= $form->field($model, 'nome_paciente')->textInput() ?>
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
                array('/caso-doenca/index'),
                array('class'=>'link','rel'=>'tooltip','data-role'=>'cancel','data-title'=>'Ir à lista de Caso Doencas')
            );
            ?>
        </div>

	<?php ActiveForm::end(); ?>

</div>
