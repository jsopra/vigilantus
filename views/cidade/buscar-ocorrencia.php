<?php
use yii\helpers\Html;
use app\helpers\models\MunicipioHelper;
use yii\helpers\Url;

$this->title = 'Acompanhar Ocorrência';
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['view', 'id' => $cliente->id, 'rotulo' => $cliente->rotulo]];
$this->params['breadcrumbs'][] = 'Detalhes';
?>
<div class="row">
    <div class="col-md-6">
        <h1>
            <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
            <a href="<?= Url::to(['cidade/view', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?>
            </a>
        </h1>
    </div>
</div>
<form class="form-inline" action="/cidade/buscar-ocorrencia" method="get">
    <p>
        Digite o <strong>número do protocolo</strong> que você recebeu
        ao registrar a ocorrência e clique em <strong>acompanhar</strong>.
    </p>
    <?php if ($hash) : ?>
    <div class="alert alert-danger">Ocorrência inválida. Confira o número do protocolo digitado.</div>
    <?php endif; ?>
    <div class="form-group">
        <input type="hidden" name="id" value="<?= $cliente->id; ?>" />
        <input type="text" class="form-control input-lg" name="hash" placeholder="Nº do protocolo da ocorrência" value="<?= $hash ?>" />
    <div class="form-group">
    </div>
        <button id="enviar" class="btn btn-primary btn-lg">Acompanhar</button>
    </div>
</form>
