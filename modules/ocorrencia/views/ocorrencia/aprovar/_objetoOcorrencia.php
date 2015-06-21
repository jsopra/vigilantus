<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\web\JsExpression;
?>

<br />

<div class="row">
    <div class="col-xs-4">
        <?= $form->field($model, 'numero_controle'); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-4">
        <?= Html::activeLabel($model, 'bairro_id'); ?>
        <p class="form-control-static"><?= Html::encode($model->bairro->nome) ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-3">
        <?php
        $quarteiroes = BairroQuarteirao::find()->doBairro($model->bairro_id)->orderBy('numero_quarteirao')->all();
        echo $form->field($model, 'bairro_quarteirao_id')->dropDownList(ArrayHelper::map($quarteiroes, 'id', 'numero_quarteirao'), ['prompt' => 'Selecione...']);
        ?>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'endereco'); ?>
        <p class="form-control-static"><?= Html::encode($model->endereco) ?></p>
        <?= Html::error($model, 'endereco'); ?>
    </div>
</div>

<div class="row bairro-hide">
    <div class="col-xs-8">
        <?= $form->field($model, 'imovel_id')->widget(
            Select2::classname(),
            [
                'options' => ['placeholder' => 'Buscar por um imóvel...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => "' . Url::toRoute(['ocorrencia/imoveis', 'bairro_id' => $model->bairro_id]) . '",
                        'dataType' => 'json',
                        'data' => new JsExpression('function(term,page) { return {q:term}; }'),
                        'results' => new JsExpression('function (data, page) {
                            return {
                                results : $.map(data, function (item) {
                                    return {
                                        text:item.name, slug:item.name, id:item.id
                                    }
                                })
                            };
                        }'),
                    ],
                ],
            ]
        );
        ?>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-3">
        <?php
        echo $form->field($model, 'tipo_imovel')->dropDownList(\app\models\OcorrenciaTipoImovel::getDescricoes(), ['prompt' => 'Selecione...']);
        ?>
    </div>

    <div class="col-xs-3">
        <?php
        $tipos = \app\models\OcorrenciaTipoProblema::find()->ativos()->orderBy('nome')->all();
        echo $form->field($model, 'ocorrencia_tipo_problema_id')->dropDownList(ArrayHelper::map($tipos, 'id', 'nome'), ['prompt' => 'Selecione...']);
        ?>
    </div>
</div>

<?php if($model->pontos_referencia) : ?>
<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'pontos_referencia'); ?>
        <p class="form-control-static"><?= Html::encode($model->pontos_referencia) ?></p>
        <?= Html::error($model, 'pontos_referencia'); ?>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'mensagem'); ?>
        <p class="form-control-static"><?= Html::encode($model->mensagem) ?></p>
        <?= Html::error($model, 'mensagem'); ?>
    </div>
</div>

<?php if($model->anexo) : ?>
<div class="row" style="margin-top: 2em;">
    <div class="col-xs-12">
        <a href="<?= Url::to(['ocorrencia/anexo', 'id' => $model->id]); ?>"><i class="glyphicon glyphicon-paperclip"></i> Download do anexo</a>
    </div>
</div>
<?php endif; ?>
