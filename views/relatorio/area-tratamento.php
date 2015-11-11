<?php
use app\models\Bairro;
use app\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Áreas de Tratamento';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_filtroRelatorioAreaTratamento', ['model' => $model, 'usaData' => false]); ?>

<?php echo $this->render('_menuRelatorioAreaTratamento', []); ?>

<br />

<?php
echo GridView::widget([
    'dataProvider' => $model->dataProviderAreasTratamento,
    'filterModel' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header' => 'Bairro',
            'value' => function ($model, $index, $widget) {
                return Html::encode($model->bairro->nome);
            },
        ],
        'numero_quarteirao',
        'numero_quarteirao_2',
        [
            'class' => 'app\extensions\grid\ModalColumn',
            'iconClass' => 'icon-search opacity50',
            'modalId' => 'focos-detalhes',
            'modalAjaxContent' => function ($model, $index, $widget) {
                return Url::toRoute(array('relatorio/focos-area-tratamento', 'idQuarteirao' => Html::encode($model->id)));
            },
            'requestType' => 'GET',
            'header' => 'Focos relacionados ao Quarteirão',
            'linkTitle' => 'Ver Focos',
            'value' => function ($model, $index, $widget) {
                return 'Ver Focos';
            },
            'options' => [
                'width' => '15%',
            ]
        ],
    ],
]);
