<?php

use yii\helpers\Html;
use app\widgets\GridView;
use app\models\Bairro;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaTipoProblema;
use app\helpers\models\OcorrenciaHelper;
use app\models\Configuracao;
use yii\helpers\Url;

$this->title = 'Ocorrências';
$this->params['breadcrumbs'][] = $this->title;

$diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, \Yii::$app->user->identity->cliente->id);
$diasVemelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, \Yii::$app->user->identity->cliente->id);
?>
<div class="ocorrencia-index">

	<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Ocorrência',
                    Yii::$app->urlManager->createUrl('ocorrencia/ocorrencia/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
                        'id' => 'stepguide-create-ocorrencia',
                    ]
                );
            },
            'batch' => function() {
                return Html::a(
                    'Importar Arquivo de Ocorrências',
                    Url::to(['batch']),
                    [
                        'class' => 'btn btn-flat success',
                        'id' => 'stepguide-create-carga-ocorrencias',
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
                'filter' => OcorrenciaStatus::getDescricoes(),
                'value' => function ($model, $index, $widget) {
                    return OcorrenciaStatus::getDescricao($model->status);
                }
            ],
            [
                'attribute' => 'data_fechamento',
                'header' => 'Fechamento',
                'filter' => ['0' => 'Aberto', '1' => 'Fechado'],
                'value' => function ($model, $index, $widget) {
                    return $model->getFormattedAttribute('data_fechamento');
                }
            ],
            [
                'attribute' => 'ocorrencia_tipo_problema_id',
                'filter' => OcorrenciaTipoProblema::listData('nome'),
                'value' => function ($model, $index, $widget) {
                    return $model->getDescricaoTipoProblema();
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
                'class' => 'app\extensions\grid\ModalColumn',
                'iconClass' => 'icon-search opacity50',
                'modalId' => 'averiguacoes-detalhes',
                'modalAjaxContent' => function ($model, $index, $widget) {
                    return Url::toRoute(['ocorrencia/ver-averiguacoes', 'id' => $model->id]);
                },
                'requestType' => 'GET',
                'header' => 'Averiguações',
                'linkTitle' => 'Ver Averiguações',
                'value' => function ($model, $index, $widget) {
                    $quantidade = $model->quantidadeAveriguacoes;
                    return $quantidade > 0  ? 'Ver (' . $quantidade . ')' : '';
                },
                'hideLinkExpression' => function ($model, $index, $widget) {
                    return  $model->quantidadeAveriguacoes == 0;
                },
                'options' => [
                    'width' => '10%',
                ]
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

                    if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
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
                'class' => 'app\components\OcorrenciaColumn',
                'template' => '{detalhes} {aprovar} {reprovar} {mudar-status} {anexo} {tentativa-averiguacao} {comprovante}',
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
                        intro: "Este é o painel de gestão de Ocorrências. Uma ocorrência pode ser cadastrada por você, ou mesmo recebida através da página pública ou das redes sociais"
                    },
                    {
                        element: "#stepguide-create-ocorrencia",
                        intro: "Você pode transcrever uma ocorrência recebida por uma forma não automatizada"
                    },
                    {
                        element: "#stepguide-create-carga-ocorrencias",
                        intro: "Ou também fazer uma carga de ocorrências no sistema"
                    },
                    {
                        element: "thead",
                        intro: "Todas as ocorrências ficarão listadas abaixo. Você poderá filtrar por status, tipo de problema, bairro, ou acompanhar indicadores de atendimento da ocorrência. A última coluna traz alguns ícones que darão continuidade à uma ocorrência: ver detalhes, baixar anexo, aprovar, reprovar e informar alteração de status"
                    },
                ],
            });

            intro.start();
        })
    ';
    $view->registerJs($script);
}
