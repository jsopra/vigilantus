<?php
use app\helpers\models\MunicipioHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\helpers\ArrayHelper;
use app\helpers\models\ImovelHelper;

$municipio = $model->cliente->municipio;
?>

<div style="text-align: center;">
    <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
</div>

<div style="text-align: center;">
    <p style="padding: 0; margin: 1em 0 0.3em 0;">SECRETARIA MUNICIPAL DE SAÚDE</p>
    <p style="padding: 0; margin: 0.3em 0;">DEPTO. DE VIGILÂNCIA SANITÁRIA</p>
        <p style="padding: 0; margin: 1em 0 0 0; font-weight: bold;">COMPROVANTE DE OCORRÊNCIA</p>
        <p style="padding: 0; margin: 1em 0 0 0;">Protocolo nº <strong><?= $model->protocolo; ?></strong></p>
    </div>
</div>

<br />

<table>
    <tr>
        <td colspan="2"><strong>Endereço:</strong> <?= $model->endereco; ?></td>
    </tr>
    <tr>
        <td><strong><?= Html::activeLabel($model, 'bairro_id'); ?></strong>: <?php echo $model->bairro->nome; ?></td>
        <td><strong><?= Html::activeLabel($model, 'bairro_quarteirao_id'); ?></strong>: <?= $model->bairro_quarteirao_id ? Html::encode($model->bairroQuarteirao->numero_quarteirao) : Html::encode(null); ?></td>
    </tr>
    <tr>
        <td><strong><?= Html::activeLabel($model, 'tipo_imovel'); ?></strong>: <?= \app\models\OcorrenciaTipoImovel::getDescricao($model->tipo_imovel); ?></td>

        <td><strong><?= Html::activeLabel($model, 'ocorrencia_tipo_problema_id'); ?></strong>: <?= $model->ocorrencia_tipo_problema_id ? Html::encode($model->ocorrenciaTipoProblema->nome) : Html::encode(null); ?></td>
    </tr>

    <?php if($model->pontos_referencia) : ?>
    <tr>
        <td colspan="2">
            <strong><?= Html::activeLabel($model, 'pontos_referencia'); ?></strong>: <?= $model->pontos_referencia; ?>
        </td>
    </tr>

    <?php endif; ?>

    <tr>
        <td colspan="2">
            <strong><?= Html::activeLabel($model, 'mensagem'); ?></strong>: <?= $model->mensagem; ?>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <strong>Anexo</strong>: <?= $model->anexo ? Html::encode('Sim') : Html::encode('Não'); ?>
        </td>
    </tr>

</table>
