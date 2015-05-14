<?php
use yii\helpers\Html;
use app\widgets\GridView;
use app\models\DenunciaHistoricoTipo;
use app\models\DenunciaStatus;
use app\models\Usuario;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

$this->title = 'Detalhes de Denúncia #' . $model->protocolo;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index', 'id' => $cliente->id]];
$this->params['breadcrumbs'][] = 'Detalhes';
?>

<div class="row">
    <div class="col-md-6">

        <h1><?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>&nbsp;&nbsp;<a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a></h1>
    </div>

    <div class="col-md-3 col-md-offset-3" style="margin-top: 1em;">
        <?= Html::a(
            '<i class="glyphicon glyphicon-download-alt"></i> Baixar Comprovante de Denúncia',
            Yii::$app->urlManager->createUrl(['cidade/comprovante-denuncia', 'id' => $cliente->id, 'hash' => $model->hash_acesso_publico]),
            [
                'class' => 'btn btn-primary',
            ]
        );
        ?>
    </div>
</div>

<div class="denuncia-detalhes">

    <h1 style="padding-bottom: 0; margin-bottom: 0.3em;">Protocolo nº: <strong><?= $model->protocolo; ?></strong></h1>

    <p style="color: #797979;"><strong>Anote o seu número de protocolo. Com ele você poderá acompanhar o andamento da denúncia a qualquer momento.</strong></p>

    <br />

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Objeto da denúncia',
                'content' => $this->render('//../modules/denuncia/views/denuncia/detalhe/_detalheObjetoDenuncia', ['model' => $model]),
                'active' => false,
            ],
            [
                'label' => 'Histórico',
                'content' => $this->render('//../modules/denuncia/views/denuncia/detalhe/_detalheHistorico', ['model' => $model, 'dataProvider' => $dataProvider, 'isExportable' => false]),
                'active' => true
            ],
            [
                'label' => 'Mapa',
                'content' => $this->render('//../modules/denuncia/views/denuncia/detalhe/_detalheMapa', ['model' => $model]),
                'active' => false
            ],
        ]
    ]);
    ?>
</div>
