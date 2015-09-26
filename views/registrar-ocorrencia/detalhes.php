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
    <h2>Dê <strong>detalhes</strong> que ajudem a <strong>entender</strong> a situação</h2>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'mensagem')->label('Está tendo problemas com:')->textArea(['rows' => 5, 'placeholder' => 'Quaisquer informações relevantes sobre o problema.']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'file')->fileInput()->hint('Adicione <strong>uma foto</strong> ou qualquer outro arquivo que possa ser útil') ?>
            </div>
        </div>

        <div class="form-group text-center">
            <?= Html::submitButton('Próximo passo', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>