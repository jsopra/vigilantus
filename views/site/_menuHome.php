<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Alert;
use yii\helpers\Url;
?>

<h1>
    <?php if($municipio->brasao) : ?>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <?php endif; ?>

    <?= Html::encode('PM de ' . $municipio->nome . '/' . $municipio->sigla_estado) ?>
</h1>

<h2><?= Html::encode($this->title) ?></h2>

<br />

<div class="row">
    <?php
    if($qtdeVerde > 0) {
        echo '<div class="col-xs-6">';
        echo Alert::widget([
            'options' => ['class' => 'alert-warning',],
            'body' => '<a href="' . Url::to(['denuncia/denuncia/index', 'DenunciaSearch[qtde_dias_aberto]' => $diasVerde, 'DenunciaSearch[data_fechamento]' => '0']) . '">Existem ' . $qtdeVerde . ' denúncias abertas há mais de ' . $diasVerde . ' dias</a>'
        ]);
        echo '</div>';
    }

    if($qtdeVermelho > 0) {
        echo '<div class="col-xs-6">';
        echo Alert::widget([
            'options' => ['class' => 'alert-danger',],
            'body' => '<a href="' . Url::to(['denuncia/denuncia/index', 'DenunciaSearch[qtde_dias_aberto]' => $diasVermelho, 'DenunciaSearch[data_fechamento]' => '0']) . '">Existem ' . $qtdeVermelho . ' denúncias abertas há mais de ' . $diasVermelho . ' dias</a>'
        ]);
        echo '</div>';
    }
    ?>
</div>

<br />

<?php
echo Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => 'RG',
            'url' => ['site/home'],
            'options' => ['id' => 'rg']
        ],

        [
            'label' => 'Focos',
            'url' => ['site/resumo-focos'],
            'options' => ['id' => 'focos']
        ],
    ],
]);
?>
