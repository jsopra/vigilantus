<?php
use app\models\OcorrenciaTipoProblema;
use app\models\Ocorrencia;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Registre uma ocorrência para Prefeitura Municipal de ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>

<?= $this->render('_header', ['municipio' => $municipio, 'activeTab' => $activeTab]); ?>

<div class="bloco-etapa-registro-ocorrencia">
    <h2>Dê <strong>detalhes</strong> que ajudem a <strong>entender</strong> a situação</h2>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <div class="row">
        <?php
        $tipos = OcorrenciaTipoProblema::find()->doCliente($municipio->cliente->id)->ativos()->orderBy('nome')->all();

        echo $form->field($model, 'ocorrencia_tipo_problema_id')
            ->label('Problema:')
            ->widget(
            Select2::classname(),
            [
                'data' => ['0' => ''] + ArrayHelper::map($tipos, 'id', 'nome') + ['' => 'Outros'],
                'pluginOptions' => ['allowClear' => true],
            ]
        );
        ?>
    </div>

    <div class="row" id="bloco-outro-tipo-problema">
        <?= $form->field($model, 'descricao_outro_tipo_problema')->label('Descreva o problema') ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'tipo_registro')->dropDownList(Ocorrencia::getTiposRegistros()) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'file')->fileInput()->hint('Adicione <strong>uma foto</strong> ou qualquer outro arquivo que possa ser útil') ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'mensagem')->textArea(['rows' => 5, 'placeholder' => 'Quaisquer informações relevantes sobre o problema.']) ?>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Próximo passo', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs('
$(document).ready(function(){
    var checarTipoProblema = function() {
        if ($("#ocorrenciaform-ocorrencia_tipo_problema_id").val()) {
            $("#bloco-outro-tipo-problema").hide();
        } else {
            $("#bloco-outro-tipo-problema").show();
        }
    };
    $("#ocorrenciaform-ocorrencia_tipo_problema_id").change(checarTipoProblema);
    checarTipoProblema();
});');
