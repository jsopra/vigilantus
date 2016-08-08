<?php
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'Registre uma ocorrência para Prefeitura Municipal de ' . Html::encode($municipio->nome . '/' . $municipio->sigla_estado);
?>

<?= $this->render('_header', ['municipio' => $municipio, 'activeTab' => $activeTab]); ?>

<div class="bloco-etapa-registro-ocorrencia">
    <h2>
        <strong><span style="font-size: 1.6em;">Identifique-se</span></strong> e receba atualizações da ocorrência.

        <br /><br />

        A informação é <strong><span style="font-size: 1.6em;">sigilosa!</span></strong>
    </h2>

    <?php $form = ActiveForm::begin(['options' => []]); ?>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'nome')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7 col-xs-12">
                <?= $form->field($model, 'email')->textInput()->hint('<strong>Ajude a fiscalizar!</strong> Preenchendo o email, você receberá automaticamente as atualizações da ocorrência. ') ?>
            </div>

            <div class="col-md-5 col-xs-12">
                <?= $form->field($model, 'telefone')->widget(
                    MaskedInput::className(),
                    ['mask' => ['(99) 9999-9999', '(99) 99999-9999']]
                )
                ?>
            </div>
        </div>

        <div class="form-group text-center text-xs-center">
            <?= Html::submitButton('Registrar Ocorrência', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
