<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use app\models\Configuracao;

$this->title = 'Detalhes de Denúncia #' . $model->protocolo;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Detalhes';
?>
<div class="denuncia-detalhes">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        '<i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar às denúncias',
        Yii::$app->urlManager->createUrl('denuncia/denuncia/index'),
        [
            'class' => 'btn btn-link',
        ]
    );
    ?>

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
