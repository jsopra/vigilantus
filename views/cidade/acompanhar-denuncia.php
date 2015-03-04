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
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Detalhes';
?>

<div class="row">
    <div class="col-md-6">

        <h1><?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>&nbsp;&nbsp;<a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a></h1>
    </div>

    <div class="col-md-3 col-md-offset-3" style="margin-top: 1em;">
        <div class="text-right">
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&appId=634366506660294&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-share-button" data-href="" data-layout="button_count"></div>
        </div>
        <div class="text-right" style="margin-top: 1em; margin-right: -3em;">
            <a href="https://twitter.com/share" class="twitter-share-button" data-via="BrasilSemDengue" data-lang="pt">Tweetar</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </div>
    </div>
</div>

<div class="denuncia-detalhes">

    <h1><?= Html::encode($this->title) ?></h1>

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
