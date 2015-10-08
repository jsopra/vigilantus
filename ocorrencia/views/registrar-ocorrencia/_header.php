<?php
use app\helpers\models\MunicipioHelper;
use app\models\Configuracao;
use app\widgets\wizard\Wizard;
use yii\helpers\Url;
use yii\helpers\Html;

$setorUtiliza = Configuracao::getValorConfiguracaoParaCliente(
    Configuracao::ID_SETOR_UTILIZA_FERRAMENTA,
    Yii::$app->user->identity->cliente->id
);
?>

<header class="header-registro-ocorrencia">
    <h1>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
        <a href="<?= Url::to(['cidade/view', 'slug' => $municipio->slug]); ?>">
            <?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?>
        </a>
        <?php if ($setorUtiliza) : ?>
        <small><?= Html::encode($setorUtiliza) ?></small>
        <?php endif; ?>
    </h1>
    <p>
        A ocorrência será avaliada pela <strong>Prefeitura</strong>
        e você poderá <strong>acompanhá-la</strong>
    </p>
    <?= Wizard::widget([
        'tabs' => [
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/index', 'slug' => $municipio->slug],
                'desc' => 'Local'
            ],
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/detalhes', 'slug' => $municipio->slug],
                'desc' => 'Detalhes'
            ],
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/identificacao', 'slug' => $municipio->slug],
                'desc' => 'Identificação'
            ],
        ],
        'activeTab' => $activeTab,
    ]);
    ?>
</header>
