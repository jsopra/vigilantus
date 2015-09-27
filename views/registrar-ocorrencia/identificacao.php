<?php
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'Registre uma ocorrência para Prefeitura Municipal de ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>

<?= $this->render('_header', ['municipio' => $municipio, 'cliente' => $cliente, 'activeTab' => $activeTab]); ?>

<div class="bloco-etapa-registro-ocorrencia">
    <h2>
        <strong>Identifique-se</strong> e receba atualizações da ocorrência
        <strong>em seu email</strong>. A informação é <strong>sigilosa</strong>!
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
                <?= Html::activeLabel($model, 'telefone') ?>
                <?php
                echo MaskedInput::widget([
                    'model' => $model,
                    'name' => "Ocorrencia[telefone]",
                    'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                ]);
                ?>
            </div>
        </div>

        <div class="form-group text-center">
            <?= Html::submitButton('Registrar Ocorrência', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
