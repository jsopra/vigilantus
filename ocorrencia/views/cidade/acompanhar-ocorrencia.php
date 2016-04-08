<?php
use yii\helpers\Html;
use app\widgets\GridView;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
use app\models\Usuario;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

$this->title = 'Detalhes de Ocorrência #' . $model->protocolo;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['view', 'slug' => $municipio->slug]];
$this->params['breadcrumbs'][] = 'Detalhes';
?>
<div class="row header-detalhes-ocorrencia">
    <div class="col-md-6">
        <h1>
            <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
            <a href="<?= Url::to(['view', 'slug' => $municipio->slug]); ?>">
                <?= Html::encode($municipio->nome . ' / ' . $municipio->sigla_estado) ?>
            </a>
        </h1>
    </div>
    <div class="col-md-6" style="margin-top: 1em;">
        <?= Html::a(
            '<i class="glyphicon glyphicon-download-alt"></i> baixar comprovante',
            Url::to(['comprovante-ocorrencia', 'slug' => $municipio->slug, 'hash' => $model->hash_acesso_publico]),
            ['class' => 'btn btn-primary btn-lg pull-right']
        );
        ?>
    </div>
</div>
<?php
$urlOcorrencia = Url::to(['view', 'slug' => $municipio->slug], true);
$descricaoTweet = 'Registrei uma ocorrência para a Sec. de Saúde de ' . $municipio->nome . '. Seja a mudança da sua cidade! Faça seu contato em';
?>
<div class="ocorrencia-detalhes">
    <h1 style="padding-bottom: 0; margin-bottom: 0.3em;">Protocolo nº: <strong><?= $model->protocolo; ?></strong></h1>
    <p style="color: #797979;"><strong>Anote o seu número de protocolo. Com ele você poderá acompanhar o andamento da ocorrência a qualquer momento.</strong></p>
    <p>
        <div class="fb-share-button" data-href="<?= $urlOcorrencia ?>" data-layout="button_count"></div>
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= $urlOcorrencia ?>" data-text="<?= $descricaoTweet ?>" data-lang="pt">Tweetar</a>
    </p>

    <br />

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Objeto da ocorrência',
                'content' => $this->render('//../ocorrencia/views/ocorrencia/detalhe/_detalheObjetoOcorrencia', ['model' => $model]),
                'active' => false,
            ],
            [
                'label' => 'Histórico',
                'content' => $this->render(
                    '//../ocorrencia/views/ocorrencia/detalhe/_detalheHistorico',
                    [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'isExportable' => false,
                        'historicos' => $historicos,
                    ]
                ),
                'active' => true
            ],
            [
                'label' => 'Mapa',
                'content' => $this->render('//../ocorrencia/views/ocorrencia/detalhe/_detalheMapa', ['model' => $model]),
                'active' => false,
                'options' => ['id' => 'aba-mapa'],
            ],
        ]
    ]);
    ?>
</div>

<style>
.alert {
    font-size: 1.5em;
}
</style>
