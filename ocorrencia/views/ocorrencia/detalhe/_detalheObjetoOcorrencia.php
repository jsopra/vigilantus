<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\helpers\models\ImovelHelper;
?>

<br />

<dl class="dl-horizontal">
    <?php if ($model->numero_controle) : ?>
    <dt><?= Html::activeLabel($model, 'numero_controle') ?></dt>
    <dd><?= Html::encode($model->numero_controle) ?></dd>
    <?php endif; ?>

    <dt><?= Html::activeLabel($model, 'bairro_id') ?></dt>
    <dd><?= $model->bairro ? Html::encode($model->bairro->nome) : null ?></dd>

    <?php if ($model->bairro_quarteirao_id) : ?>
    <dt><?= Html::activeLabel($model, 'bairro_quarteirao_id') ?></dt>
    <dd><?= $model->bairro_quarteirao_id ? Html::encode($model->bairroQuarteirao->numero_quarteirao) : null ?></dd>
    <?php endif; ?>

    <?php if ($model->imovel_id) : ?>
    <dt><?= Html::activeLabel($model, 'imovel_id') ?></dt>
    <dd><?= $model->imovel_id ? Html::encode(ImovelHelper::getEnderecoCompleto($model->imovel)) : null ?></dd>
    <?php endif; ?>

    <dt><?= Html::activeLabel($model, 'endereco') ?></dt>
    <dd><?= Html::encode($model->endereco) ?>

    <dt><?= Html::activeLabel($model, 'tipo_imovel') ?></dt>
    <dd><?= Html::encode(\app\models\OcorrenciaTipoImovel::getDescricao($model->tipo_imovel)) ?></dd>

    <dt><?= Html::activeLabel($model, 'ocorrencia_tipo_problema_id') ?></dt>
    <dd><?= Html::encode($model->getDescricaoTipoProblema()) ?></dd>

    <dt><?= Html::activeLabel($model, 'tipo_registro') ?></dt>
    <dd><?= Html::encode(\app\models\Ocorrencia::getTiposRegistros()[$model->tipo_registro]) ?></dd>

    <?php if ($model->pontos_referencia) : ?>
    <dt><?= Html::activeLabel($model, 'pontos_referencia') ?></dt>
    <dd><?= Html::encode($model->pontos_referencia) ?></dd>
    <?php endif; ?>

    <dt><?= Html::activeLabel($model, 'mensagem') ?></dt>
    <dd><?= Html::encode($model->mensagem) ?></dd>
</dl>

<?php if ($model->anexo) : ?>
<p>
    <a href="<?= Url::to(['/ocorrencia/ocorrencia/anexo', 'id' => $model->id]); ?>">
        <i class="glyphicon glyphicon-paperclip"></i> Download do anexo
    </a>
</p>
<?php endif; ?>
