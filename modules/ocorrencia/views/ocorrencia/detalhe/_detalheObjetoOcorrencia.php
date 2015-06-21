<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\helpers\models\ImovelHelper;
?>

<br />

<p>
    <strong><?= Html::activeLabel($model, 'numero_controle') ?></strong><br />
    <?= Html::encode($model->numero_controle) ?>
</p>

<p>
    <strong><?= Html::activeLabel($model, 'bairro_id') ?></strong><br />
    <?= Html::encode($model->bairro->nome) ?>
</p>

<?php if ($model->bairro_quarteirao_id) : ?>
<p>
    <strong><?= Html::activeLabel($model, 'bairro_quarteirao_id') ?></strong><br />
    <?= $model->bairro_quarteirao_id ? Html::encode($model->bairroQuarteirao->numero_quarteirao) : null ?>
</p>
<?php endif; ?>

<?php if ($model->imovel_id) : ?>
<p>
    <strong><?= Html::activeLabel($model, 'imovel_id') ?></strong><br />
    <?= $model->imovel_id ? Html::encode(ImovelHelper::getEnderecoCompleto($model->imovel)) : null ?>
</p>
<?php endif; ?>

<p>
    <strong><?= Html::activeLabel($model, 'endereco') ?></strong><br />
    <?= Html::encode($model->endereco) ?>
</p>

<p>
    <strong><?= Html::activeLabel($model, 'tipo_imovel') ?></strong><br />
    <?= Html::encode(\app\models\OcorrenciaTipoImovel::getDescricao($model->tipo_imovel)) ?>
</p>

<p>
    <strong><?= Html::activeLabel($model, 'ocorrencia_tipo_problema_id') ?></strong><br />
    <?= $model->ocorrencia_tipo_problema_id ? Html::encode($model->ocorrenciaTipoProblema->nome) : null ?>
</p>

<?php if ($model->pontos_referencia) : ?>
<p>
    <strong><?= Html::activeLabel($model, 'pontos_referencia') ?></strong><br />
    <?= Html::encode($model->pontos_referencia) ?>
</p>
<?php endif; ?>

<p>
    <strong><?= Html::activeLabel($model, 'mensagem') ?></strong><br />
    <?= Html::encode($model->mensagem) ?>
</p>

<?php if ($model->anexo) : ?>
<p>
    <a href="<?= Url::to(['ocorrencia/anexo', 'id' => $model->id]); ?>">
        <i class="glyphicon glyphicon-paperclip"></i> Download do anexo
    </a>
</p>
<?php endif; ?>
