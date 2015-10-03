<?php
use yii\helpers\Html;
use app\models\DepositoTipo;
use app\models\EspecieTransmissor;
use app\models\ImovelTipo;
use app\widgets\GridView;
use app\helpers\models\ImovelHelper;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_filtroRelatorioAreaTratamento', ['model' => $model, 'usaData' => false]); ?>

<?php echo $this->render('_menuRelatorioAreaTratamento', []); ?>

<br />

<?php

echo GridView::widget([
    'dataProvider' => $model->dataProviderAreasFoco,
    'filterModel' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header' => 'Bairro',
            'value' => function ($model, $index, $widget) {
                return $model->bairroQuarteirao->bairro->nome;
            },
            'options' => ['style' => 'width: 30%']
        ],
        [
            'header' => 'Quarteirao',
            'value' => function ($model, $index, $widget) {
                return $model->bairroQuarteirao->getNumero_sequencia();
            },
            'options' => ['style' => 'width: 30%']
        ],
        [
            'attribute' => 'imovel_id',
            'value' => function ($model, $index, $widget) {
                return $model->imovel ? ImovelHelper::getEndereco($model->imovel) : 'Vinculado à Quarteirão';
            },
            'options' => ['style' => 'width: 30%']
        ],
        [
            'attribute' => 'tipo_deposito_id',
            'value' => function ($model, $index, $widget) {
                return $model->tipoDeposito->sigla ? $model->tipoDeposito->sigla : $model->tipoDeposito->descricao;
            }
        ],
        [
            'attribute' => 'especie_transmissor_id',
            'value' => function ($model, $index, $widget) {
                return $model->especieTransmissor->nome;
            }
        ],
        [
            'format' => 'raw',
            'header' => 'Datas',
            'value' => function ($model, $index, $widget) {
                $str = '';
                foreach(['data_entrada', 'data_exame', 'data_coleta'] as $item)
                    $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->getFormattedAttribute($item));

                return $str;
            },
            'options' => ['style' => 'width: 20%;']
        ],
        [
            'format' => 'raw',
            'header' => 'Quantidades',
            'value' => function ($model, $index, $widget) {
                $str = '';
                foreach(['quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos'] as $item)
                    $str .= Html::tag('p', '<strong>' . $model->getAttributeLabel($item) . ':</strong> ' . $model->$item);

                return $str;
            },
            'options' => ['style' => 'width: 20%;']
        ],
    ],
]);
