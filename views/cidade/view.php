<?php
use app\helpers\models\MunicipioHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Ocorrências – ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>
<h1 class="text-center">
    <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>">
        <?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?>
    </a>
</h1>
<p class="text-center">
    Contamos com a sua ajuda para tornar o nosso município melhor.
</p>
<p class="text-center bloco-botoes-ocorrencias">
    <a href="<?= Url::to(['registrar-ocorrencia/index', 'id' => $cliente->id]) ?>" class="btn btn-danger btn-lg">
        <i class="fa fa-plus"></i>
        registrar ocorrência
    </a>
    <a href="<?= Url::to(['cidade/acompanhar-ocorrencia', 'id' => $cliente->id]) ?>" class="btn btn-success btn-lg">
        <i class="fa fa-eye"></i>
        acompanhar ocorrência
    </a>
</p>
<div class="bloco-numero-ocorrencias text-center">
    <p><strong class="recebidas"><?= $numeroOcorrenciasRecebidas ?></strong> ocorrências recebidas,</p>
    <p><strong class="atendidas"><?= $percentualOcorrenciasAtendidas ?>%</strong> já foram atendidas.</p>
    <p class="fonte-informacao">(informações coletadas desde <?= $dataPrimeiraOcorrencia ?>)</p>
</div>
<div class="panel panel-default text-center">
    <div class="panel-heading mapa-focos-chamada">
        <p>
            Será que os transmissores da <strong>Dengue</strong> e da
            <strong>Chikungunya</strong> vivem perto de você?
        </p>
        <p>
            <a href="<?= Url::to(['cidade/mapa-focos', 'id' => $cliente->id]) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                Confira no mapa
            </a>
        </p>
    </div>
</div>
