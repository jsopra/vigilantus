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

<div style="margin-top: 2em;">

    <p class="text-center" style="color: #000; font-size: 1.3em;">Dê <strong>detalhes</strong> que ajudem a <strong>entender</strong> a situação</p>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

        <div class="row" style="margin-top: 3em;">
            <div class="col-xs-12">
                <?= $form->field($model, 'file')->fileInput()->hint('Adicione <strong>uma foto</strong> ou qualquer outro arquivo que possa ser útil') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'mensagem')->textArea(['rows' => 5, 'placeholder' => 'Qualquer outra informação relevante...']) ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::submitButton('Próximo', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>