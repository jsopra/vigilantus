<?php

use app\models\Bairro;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BoletimRgFechamentoSearch $searchModel
 */

$this->title = 'Resumo do Reconhecimento Geográfico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resumo-rg-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

        <div class="row" id="dadosPrincipais">
            <div class="col-xs-4">
                <?= $form->field($model, 'bairro_id')->dropDownList(Bairro::listData('nome'), ['prompt' => 'Selecione…']) ?>
            </div>

            <div class="col-xs-2">
                <?= $form->field($model, 'lira')->dropDownList([0 => 'Todos', 1 => 'Lira']) ?>
            </div>

            <div class="col-xs-2" style="padding-top: 20px;">
                <?= Html::submitButton('Gerar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if ($solicitado) : ?>
<?php if ($dados = $model->getData()) : ?>
<table id="resumo-rg-bairro" class="table table-striped table-condensed table-bordered">
    <?php
    $dadosTotaisImoveis = $dadosTotaisFoco = [];

    foreach ($model->tiposImoveis as $id => $tipoImovel) {
        $dadosTotaisImoveis[$id] = 0;
    }

    $dadosTotaisImoveis['total'] = 0;
    ?>
    <thead>
        <tr>
            <th rowspan="2" class="number">Nº Quart.</th>
            <th rowspan="2" class="number">Nº Alt.</th>
            <th rowspan="2" class="number">Seq.</th>
            <th colspan="<?= count($model->tiposImoveis) ?>">TIPO DO IMÓVEL</th>
            <th rowspan="2" class="total">Total de Imóveis</th>
            <th rowspan="2" class="total">Último Foco</th>
        </tr>
        <tr>
            <?php foreach ($model->tiposImoveis as $tipoImovel) : ?>
            <th><?= $tipoImovel ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($dados as $row) :

            $totalQuarteirao = 0;

            foreach ($model->tiposImoveis as $id => $tipoImovel) {
                $dadosTotaisImoveis[$id] += $row['imoveis'][$id];
                $dadosTotaisImoveis['total'] += $row['imoveis'][$id];
                $totalQuarteirao += $row['imoveis'][$id];
            }
            ?>
        <tr class="text-center">
            <td><?= $row['quarteirao'] ?></td>
            <td><?= $row['quarteirao_numero_alternativo'] ?></td>
            <td><?= $row['quarteirao_sequencia'] ?></td>
            <?php foreach ($model->tiposImoveis as $tipo => $descricaoTipo) : ?>
            <td><?= $row['imoveis'][$tipo] ?></td>
            <?php endforeach; ?>
            <td><strong><?= $totalQuarteirao ?></strong></td>
            <td><strong><?= Yii::$app->formatter->asDate($row['data_ultimo_foco'], 'medium') ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total:</th>
            <?php foreach ($model->tiposImoveis as $tipo => $descricaoTipo) : ?>
            <td class="text-center"><?= $dadosTotaisImoveis[$tipo] ?></td>
            <?php endforeach; ?>
            <td class="text-center"><strong><?= $dadosTotaisImoveis['total'] ?></strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php else : ?>
    <p><?= Yii::t('yii', 'No results found.') ?></p>
<?php endif; ?>
<?php endif; ?>
