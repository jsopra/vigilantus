<?php
use app\helpers\models\MunicipioHelper;
use app\widgets\wizard\Wizard;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<header class="header-registro-ocorrencia">
    <h1>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
        <a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a>
    </h1>
    <p>
        A ocorrência será avaliada pela <strong>Prefeitura</strong>
        e você poderá <strong>acompanhá-la</strong>
    </p>
    <?= Wizard::widget([
        'tabs' => [
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/index', 'id' => $municipio->id],
                'desc' => 'Local'
            ],
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/detalhes', 'id' => $municipio->id],
                'desc' => 'Detalhes'
            ],
            [
                'move' => false,
                'url' => ['registrar-ocorrencia/identificacao', 'id' => $municipio->id],
                'desc' => 'Identificação'
            ],
        ],
        'activeTab' => $activeTab,
    ]);
    ?>
</header>
