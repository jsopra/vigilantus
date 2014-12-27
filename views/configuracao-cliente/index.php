<?php

use yii\helpers\Html;
use app\widgets\GridView;

$this->title = 'Configurações de Sistema';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracao-cliente-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => false,
        'exportable' => false,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            'configuracao.nome',
            'configuracao.descricao',
            [
                'attribute' => 'valor',
                'value' => function ($model, $index, $widget) {
                    return $model->configuracao->getDescricaoValor($model->cliente_id);
                }
            ],
			[
                'class' => 'app\components\ActionColumn',
                'template' => '{update}',
            ],
		],
	]); ?>

</div>
