<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Bairro;
use yii\helpers\ArrayHelper;
?>

<div class="cidade_formDenuncia">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

        <p style="color: #797979;"><strong>Objeto da denúncia</strong></p>

        <div class="row">
            <div class="col-xs-12">
                <?php
                $bairros = Bairro::find()->comQuarteiroes()->orderBy('nome')->all();
                echo $form->field($model, 'bairro_id')->dropDownList(ArrayHelper::map($bairros, 'id', 'nome'), ['prompt' => 'Selecione..']);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'endereco') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <?php
                echo $form->field($model, 'tipo_imovel')->dropDownList(\app\models\DenunciaTipoImovel::getDescricoes(), ['prompt' => 'Selecione..']);
                ?>
            </div>

            <div class="col-xs-6">
                <?php
                $tipos = \app\models\DenunciaTipoProblema::find()->ativos()->orderBy('nome')->all();
                echo $form->field($model, 'denuncia_tipo_problema_id')->dropDownList(ArrayHelper::map($tipos, 'id', 'nome'), ['prompt' => 'Selecione..']);
                ?>
            </div>
        </div>
       
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'pontos_referencia') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'mensagem')->textArea() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
        </div>

        <hr />
        
        <p style="color: #797979;"><strong>Seus dados (opcional)</strong></p>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-7">
                <?= $form->field($model, 'email')->textInput() ?>
            </div>

            <div class="col-xs-5">
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
    
        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
