<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use app\models\Configuracao;

$this->title = 'Denúncia' . ($model->protocolo ? ' #' . $model->protocolo : '');
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Detalhes';
$urlDenuncia = Url::to('/' . $model->cliente->rotulo, true);
$descricaoTweet = 'Denunciei um foco de mosquitos da dengue. Caso você também perceba algum, denuncie aqui: ';
?>
<div class="denuncia-detalhes">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-sm-6">
            <div class="fb-share-button" data-href="<?= $urlDenuncia ?>" data-layout="button_count"></div>
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= $urlDenuncia ?>" data-text="<?= $descricaoTweet ?>" data-lang="pt">Tweetar</a>
        </div>
        <div class="col-sm-6 text-right">
            <?= Html::a(
                '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar às denúncias',
                Yii::$app->urlManager->createUrl('denuncia/denuncia/index'),
                ['class' => 'btn btn-link']
            )
            ?>
            <?= Html::a(
                '<i class="glyphicon glyphicon-download-alt"></i> Baixar Comprovante de Denúncia',
                Yii::$app->urlManager->createUrl(['denuncia/denuncia/comprovante', 'id' => $model->id]),
                ['class' => 'btn btn-success']
            )
            ?>
        </div>
    </div>
    <br>
    <?php
    $qtdeDias = $model->qtde_dias_em_aberto;

    $qtdeDiasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERDE, \Yii::$app->session->get('user.cliente')->id);
    $qtdeDiasVermelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERMELHO, \Yii::$app->session->get('user.cliente')->id);

    if(!$model->data_fechamento) {

        if($qtdeDias > $qtdeDiasVermelho) {
            Yii::$app->session->setFlash('error', 'A denúncia está aberta há ' . $qtdeDias . ' dias');
        } else if($qtdeDias > $qtdeDiasVerde && $qtdeDias <= $qtdeDiasVermelho) {
            Yii::$app->session->setFlash('warning', 'A denúncia está aberta há ' . $qtdeDias . ' dias');
        } else if($qtdeDias <= $qtdeDiasVerde) {
            Yii::$app->session->setFlash('info', 'A denúncia está aberta há ' . $qtdeDias . ' dias');
        }

    }
    ?>
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Objeto da denúncia',
                'content' => $this->render('detalhe/_detalheObjetoDenuncia', ['model' => $model]),
                'active' => true
            ],
            [
                'label' => 'Dados do denunciante',
                'content' => $this->render('detalhe/_detalheDadosDenunciante', ['model' => $model]),
                'active' => false
            ],
            [
                'label' => 'Histórico',
                'content' => $this->render('detalhe/_detalheHistorico', ['model' => $model, 'dataProvider' => $dataProvider, 'isExportable' => true]),
                'active' => false
            ],
            [
                'label' => 'Mapa',
                'content' => $this->render('detalhe/_detalheMapa', ['model' => $model]),
                'active' => false
            ],
        ]
    ]);
    ?>
</div>
