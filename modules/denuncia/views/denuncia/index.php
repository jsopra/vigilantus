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
?>
<div class="denuncia-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'rowOptions' => function ($model, $index, $widget, $grid){

            $qtdeDias = $model->qtde_dias_em_aberto;

            $qtdeDiasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERDE, \Yii::$app->session->get('user.cliente')->id);
            $qtdeDiasVermelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERMELHO, \Yii::$app->session->get('user.cliente')->id);

            if($qtdeDias >= $qtdeDiasVermelho) {
                return ['style'=>'background-color: #FFA0A0;'];
            } else if($qtdeDias >= $qtdeDiasVerde) {
                return ['style'=>'background-color: #4FD190;'];
            }

            return [];
        },
        'buttons' => [
            'create' => function() {
                return Html::a(
                    'Cadastrar Denúncia',
                    Yii::$app->urlManager->createUrl('denuncia/denuncia/create'),
                    [
                        'class' => 'btn btn-flat success',
                        'data-role' => 'create',
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
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return $model->qtde_dias_em_aberto;
                }
            ],
			[
				'header' => 'Ações',
                'class' => 'app\components\DenunciaColumn',
                'template' => '{detalhes} {aprovar} {reprovar} {mudar-status} {anexo}',
            ],
		],
	]); ?>

</div>
