<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\helpers\models\ImovelHelper;
?>

<br />

<div class="row">
    <div class="col-xs-4">
        <?= Html::activeLabel($model, 'bairro_id'); ?>
        <p class="form-control-static"><?php echo $model->bairro->nome; ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-3">
        <?= Html::activeLabel($model, 'bairro_quarteirao_id'); ?>
        <p class="form-control-static"><?php echo $model->bairro_quarteirao_id ? $model->bairroQuarteirao->numero_quarteirao : null; ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-8">
       <?= Html::activeLabel($model, 'imovel_id'); ?>
        <p class="form-control-static"><?php echo $model->imovel_id ? ImovelHelper::getEnderecoCompleto($model->imovel) : null; ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'endereco'); ?>
        <p class="form-control-static"><?php echo $model->endereco; ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-3">
        <?= Html::activeLabel($model, 'tipo_imovel'); ?>
        <p class="form-control-static"><?= \app\models\DenunciaTipoImovel::getDescricao($model->tipo_imovel); ?></p>
    </div>
</div>

<br />

<div class="row">
    <div class="col-xs-3">
        <?= Html::activeLabel($model, 'denuncia_tipo_problema_id'); ?>
        <p class="form-control-static"><?= $model->denuncia_tipo_problema_id ? $model->denunciaTipoProblema->nome : null; ?></p>
    </div>
</div>

<br />

<?php if($model->pontos_referencia) : ?>
<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'pontos_referencia'); ?>
        <p class="form-control-static"><?php echo $model->pontos_referencia; ?></p>
    </div>
</div>

<br />

<?php endif; ?>

<div class="row">
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'mensagem'); ?>
        <p class="form-control-static"><?php echo $model->mensagem; ?></p>
    </div>
</div>

<br />

<?php if($model->anexo) : ?>
<div class="row" style="margin-top: 2em;">
    <div class="col-xs-12">
        <a href="<?= Url::to(['denuncia/anexo', 'id' => $model->id]); ?>"><i class="glyphicon glyphicon-paperclip"></i> Download do anexo</a>
    </div>
</div>
<?php endif; ?>
