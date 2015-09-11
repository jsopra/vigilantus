<?php
use app\helpers\MapHelper;
use app\helpers\models\MunicipioHelper;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\models\redis\FocosAtivos;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Focos em ' . $municipio->nome . '/' . $municipio->sigla_estado;
$urlOcorrencia = Url::to('/' . $cliente->rotulo, true);
$descricaoPagina = 'Acabei de registrar uma ocorrência de um foco da dengue para a Prefeitura de ' . $municipio->nome . ' - ' . $municipio->sigla_estado . '. Seja você também um cidadão e denuncie problemas de nossa cidade em ' .  $urlOcorrencia;
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::to('/img/og-sharing-preview.jpg', true)]);
$this->registerMetaTag(['property' => 'og:title', 'content' => 'Denuncie focos de mosquitos da dengue']);
$this->registerMetaTag(['property' => 'og:description', 'content' => $descricaoPagina]);

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore', 'markercluster']);
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
<p class="text-center">
    <a href="<?= Url::to(['cidade/registrar-ocorrencia', 'id' => $cliente->id]) ?>" class="btn btn-danger btn-lg">Registrar Ocorrência</a>
    <a href="<?= Url::to(['cidade/acompanhar-ocorrencia', 'id' => $cliente->id]) ?>" class="btn btn-success btn-lg">Acompanhar Ocorrência</a>
</p>

<hr>

<div class="panel panel-default text-center">
    <div class="panel-heading mapa-focos-chamada">
        <p>
            Será que os transmissores da <strong>Dengue</strong> e da
            <strong>Chikungunya</strong> vivem perto de você?
        </p>
        <p>
            <a href="<?= Url::to(['cidade/mapa-focos', 'id' => $cliente->id]) ?>" class="btn btn-success">
                <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                Confira no mapa
            </a>
        </p>
    </div>
</div>
