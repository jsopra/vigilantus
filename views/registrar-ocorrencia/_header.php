<?php
use app\helpers\models\MunicipioHelper;
use app\widgets\wizard\Wizard;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="col-md-12 text-center">
    <h1>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
        <a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a>
    </h1>
</div>

<div class="col-md-12 text-center">

    <p class="text-center" style="line-height: 1.5em; color: #585858; font-size: 1.6em;">
        A ocorrência será avaliada pela <font style="font-weight: bold; font-size: 1.05em; color: #000;">Prefeitura</font>
        e você poderá <font style="font-weight: bold; color: #000; font-size: 1.05em;">acompanhá-la</font>
    </p>

</div>

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
]); ?>
