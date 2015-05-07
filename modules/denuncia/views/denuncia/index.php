<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\Bairro;
use app\models\DenunciaStatus;
use app\models\DenunciaTipoProblema;
use app\helpers\models\DenunciaHelper;
use app\models\Configuracao;

$this->title = 'Denúncias';
$this->params['breadcrumbs'][] = $this->title;

$diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERDE, \Yii::$app->session->get('user.cliente')->id);
$diasVemelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERMELHO, \Yii::$app->session->get('user.cliente')->id);
?>
<div class="denuncia-index">

	<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Denúncia',
                    Yii::$app->urlManager->createUrl('denuncia/denuncia/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-create-denuncia',
                    ]
                );
            }
        ],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
                'attribute' => 'data_criacao',
                'options' => [
                    'width' => '10%',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_criacao');
                },
            ],
			[
                'attribute' => 'status',
                'filter' => DenunciaStatus::getDescricoes(),
                'value' => function ($model, $index, $widget) {
                    return DenunciaStatus::getDescricao($model->status);
                }
            ],
            [
                'attribute' => 'data_fechamento',
                'filter' => ['0' => 'Aberto', '1' => 'Fechado'],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_fechamento');
                }
            ],
            [
                'attribute' => 'denuncia_tipo_problema_id',
                'filter' => DenunciaTipoProblema::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->denunciaTipoProblema ? $model->denunciaTipoProblema->nome : null;
                }
            ],
			[
                'attribute' => 'bairro_id',
                'filter' => Bairro::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->bairro ? $model->bairro->nome : null;
                }
            ],
            [
                'header' => 'Qtde. Dias em aberto',
                'attribute' => 'qtde_dias_aberto',
                'filter' => [
                    1 => 'Até ' . $diasVerde . ' dias',
                    2 => 'Entre ' . $diasVerde . ' e ' . $diasVemelho . ' dias',
                    3 => 'Mais de ' . $diasVemelho . ' dias',
                ],
                'value' => function ($model, $index, $widget) {
                    return $model->qtde_dias_em_aberto;
                },
                'contentOptions' => function ($model, $index, $widget, $grid) use ($diasVerde, $diasVemelho) {

                    if(in_array($model->status, DenunciaStatus::getStatusTerminativos())) {
                        return [];
                    }

                    $qtdeDias = $model->qtde_dias_em_aberto;

                    if($qtdeDias <= $diasVerde) {
                        return ['style'=>'background-color: #4FD190; font-weight: bold;'];
                    } else if($qtdeDias > $diasVerde && $qtdeDias <= $diasVemelho) {
                        return ['style'=>'background-color: #FFFF50; font-weight: bold;'];
                    } else if($qtdeDias > $diasVemelho) {
                        return ['style'=>'background-color: #FFA0A0; font-weight: bold;'];
                    }

                    return [];
                },
            ],
			[
				'header' => 'Ações',
                'class' => 'app\components\DenunciaColumn',
                'template' => '{detalhes} {aprovar} {reprovar} {mudar-status} {anexo} {tentativa-averiguacao}',
            ],
		],
	]); ?>

</div>


<?php
if(isset($_GET['step'])) {
    $view = Yii::$app->getView();
    $script = '
        $(document).ready(function() {

            var intro = introJs();
            intro.setOption("skipLabel", "Sair");
            intro.setOption("doneLabel", "Fechar");
            intro.setOption("nextLabel", "Próximo");
            intro.setOption("prevLabel", "Anterior");
            intro.setOption("tooltipPosition", "auto");
            intro.setOption("positionPrecedence", ["left", "right", "bottom", "top"]);

            intro.setOptions({
                steps: [
                    {
                        element: "#stepguide-title",
                        intro: "Este é o painel de gestão de Denúncias. Uma denúncia pode ser cadastrada por você, ou mesmo recebida através da página pública ou das redes sociais"
                    },
                    {
                        element: "#stepguide-create-denuncia",
                        intro: "Você pode transcrever uma denúncia recebida por uma forma não automatizada"
                    },
                    {
                        element: "thead",
                        intro: "Todas as denúncias ficarão listadas abaixo. Você poderá filtrar por status, tipo de problema, bairro, ou acompanhar indicadores de atendimento da denúncia. A última coluna traz alguns ícones que darão continuidade à uma denúncia: ver detalhes, baixar anexo, aprovar, reprovar e informar alteração de status"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
