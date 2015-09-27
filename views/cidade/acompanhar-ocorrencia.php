<?php
use yii\helpers\Html;
use app\widgets\GridView;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
use app\models\Usuario;
use app\helpers\models\MunicipioHelper;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

if ($model) {
    $this->title = 'Detalhes de Ocorrência #' . $model->protocolo;
} else {
    $this->title = 'Acompanhar Ocorrência';
}
$this->params['breadcrumbs'][] = ['label' => 'Ocorrências', 'url' => ['view', 'rotulo' => $cliente->rotulo]];
$this->params['breadcrumbs'][] = 'Detalhes';
?>
<div class="row">
    <div class="col-md-6">
        <h1>
            <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
            <a href="<?= Url::to(['cidade/view', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?>
            </a>
        </h1>
    </div>
    <?php if ($model) : ?>
    <div class="col-md-6" style="margin-top: 1em;">
        <?= Html::a(
            '<i class="glyphicon glyphicon-download-alt"></i> baixar comprovante',
            Yii::$app->urlManager->createUrl(['cidade/comprovante-ocorrencia', 'id' => $cliente->id, 'hash' => $model->hash_acesso_publico]),
            ['class' => 'btn btn-primary pull-right']
        );
        ?>
    </div>
    <?php endif; ?>
</div>
<?php if (!$model) : ?>
<form class="form-inline" action="/cidade/acompanhar-ocorrencia" method="get">
    <p>
        Digite o <strong>número do protocolo</strong> da ocorrência que você
        recebeu ao registrá-la e clique em <strong>acompanhar</strong>.
    </p>
    <div class="form-group">
        <input type="hidden" name="id" value="<?= $cliente->id; ?>" />
        <input type="text" class="form-control input-lg" name="hash" placeholder="Nº do protocolo da ocorrência" value="<?= $hash ?>" />
    <div class="form-group">
    </div>
        <button id="enviar" class="btn btn-primary btn-lg">Acompanhar</button>
    </div>
    <?php if ($hash) : ?>
    <div class="alert alert-danger">Ocorrência inválida. Confira o número do protocolo digitado.</div>
    <?php endif; ?>
</form>
<?php
else :

$urlOcorrencia = Url::to('/' . $model->cliente->rotulo, true);
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
                'content' => $this->render('//../modules/ocorrencia/views/ocorrencia/detalhe/_detalheObjetoOcorrencia', ['model' => $model]),
                'active' => false,
            ],
            [
                'label' => 'Histórico',
                'content' => $this->render(
                    '//../modules/ocorrencia/views/ocorrencia/detalhe/_detalheHistorico',
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
                'content' => $this->render('//../modules/ocorrencia/views/ocorrencia/detalhe/_detalheMapa', ['model' => $model]),
                'active' => false,
                'options' => ['id' => 'aba-mapa'],
            ],
        ]
    ]);
    ?>
</div>
<?php endif; ?>
