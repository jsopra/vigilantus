<?php
use app\models\Configuracao;
use app\helpers\models\MunicipioHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Ocorrências – ' . Html::encode($municipio->nome . '/' . $municipio->sigla_estado);

$urlMunicipio = Url::to(['cidade/view', 'slug' => $municipio->slug], true);
$descricaoPagina = 'Registrei uma ocorrência para a Secretaria de Saúde de ' . $municipio->nome . '/' . $municipio->sigla_estado . '. Seja a mudança na nossa cidade! Faça seu contato em ' .  $urlMunicipio;

$descricaoPagina = 'O ponto está em área de tratamento, denuncie qualquer irregularidade!. Seja a mudança na nossa cidade! Faça seu contato em ' .  $urlMunicipio;

$this->registerMetaTag(['property' => 'og:image', 'content' => Url::to('/img/og-sharing-map.jpg', true)]);
$this->registerMetaTag(['property' => 'og:title', 'content' => 'Denuncie focos de mosquitos da dengue']);
$this->registerMetaTag(['property' => 'og:description', 'content' => $descricaoPagina]);
?>
<div class="ocorrencia-cidade-view">
<h1 class="text-xs-center">
    <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <a href="<?= Url::to(['view', 'slug' => $municipio->slug]); ?>">
        <?= Html::encode($municipio->nome . ' / ' . $municipio->sigla_estado) ?>
    </a>
    <?php if ($setor = $municipio->getSetorResponsavel()) : ?>
    <?= Html::encode($setor) ?>
    <?php endif; ?>
</h1>

<p class="text-xs-center">
    Contamos com a <strong>sua ajuda</strong> para tornar o nosso
    <strong>município melhor</strong>!
</p>

<p class="text-xs-center bloco-botoes-ocorrencias">
    <a href="<?= Url::to(['registrar-ocorrencia/index', 'slug' => $municipio->slug]) ?>" class="btn btn-primary btn-lg">
        <i class="fa fa-plus"></i>
        registrar ocorrência
    </a>
    <a href="<?= Url::to(['buscar-ocorrencia', 'slug' => $municipio->slug]) ?>" class="btn btn-default btn-secondary btn-lg">
        <i class="fa fa-eye"></i>
        acompanhar ocorrência
    </a>
</p>

<?php if ($percentualOcorrenciasAtendidas > 0) : ?>
<div class="bloco-numero-ocorrencias text-xs-center">
    <p><strong class="recebidas"><?= $numeroOcorrenciasRecebidas ?></strong> ocorrências recebidas</p>
    <p><strong class="atendidas"><?= $percentualOcorrenciasAtendidas ?>%</strong> já foram finalizadas</p>
    <p class="fonte-informacao">(informações coletadas desde <?= $dataPrimeiraOcorrencia ?>)</p>
</div>
<?php elseif ($numeroOcorrenciasRecebidas > 0) : ?>
<div class="bloco-numero-ocorrencias text-xs-center">
    <p><strong class="recebidas"><?= $numeroOcorrenciasRecebidas ?></strong> ocorrências recebidas.</p>
    <p class="fonte-informacao">(informações coletadas desde <?= $dataPrimeiraOcorrencia ?>)</p>
</div>
<?php endif; ?>

<?php if ($percentualOcorrenciasAtendidas > 0) : ?>
<div class="panel panel-default text-xs-center">
    <div class="panel-heading mapa-focos-chamada">
        <p>
            Será que o mosquito da <strong>Dengue</strong> e do
            <strong>Zika Vírus</strong> vive perto de você?
        </p>
        <p>
            <a href="<?= Url::to(['mapa-focos', 'slug' => $municipio->slug]) ?>" class="btn btn-default btn-secondary disabled">
                <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                Confira no mapa (em breve)
            </a>
        </p>
    </div>
</div>
<?php endif; ?>

</div>
