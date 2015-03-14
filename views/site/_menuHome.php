<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use app\models\Modulo;
?>

<h1 id="stepguide-title">
    <?php if($municipio->brasao) : ?>
        <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <?php endif; ?>

    <?= Html::encode('PM de ' . $municipio->nome . '/' . $municipio->sigla_estado) ?>
</h1>

<h2><?= Html::encode($this->title) ?></h2>

<br />

<?php if(\Yii::$app->user->can('Gerente') && \Yii::$app->user->getIdentity()->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) : ?>

    <div class="row" id="stepguide-denuncias-indicators">
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

<?php endif; ?>

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
