<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\BoletimRgFechamentoSearch $searchModel
 */

$this->title = 'Boletim de Resumo do Reconhecimento Geográfico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resumo-rg-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

</div>

<?php if ($searchModel->bairro_id) : ?>
<?php if ($dataProvider->getTotalCount()) : ?>
<table class="table table-striped table-condensed table-bordered">
    <?php
    $dadosTotaisImoveis = $dadosTotaisFoco = [];
    $totalAreasFoco = 0;

    foreach ($tiposImoveis as $tipoImovel) {
        $dadosTotaisImoveis[$tipoImovel->id] = $dadosTotaisFoco[$tipoImovel->id] =  0;
    }

    $dadosTotaisImoveis['total'] = $dadosTotaisFoco['total'] = 0;
    ?>
    <thead>
        <tr>
            <td rowspan="2">Número Quarteirão</td>
            <td rowspan="2">Número Alternativo</td>
            <td rowspan="2">Seq.</td>
            <td colspan="<?= count($tiposImoveis) ?>">TIPO DO IMÓVEL</td>
            <td rowspan="2">Total de Imóveis</td>
            <td rowspan="2">Área de Foco</td>
            <td colspan="<?= count($tiposImoveis) ?>">TIPO DO IMÓVEL – ÁREA DE FOCO</td>
            <td rowspan="2">Total de Imóveis</td>
        </tr>
        <tr>
            <?php foreach ($tiposImoveis as $tipoImovel) : ?>
            <td><?= $tipoImovel->sigla ? $tipoImovel->sigla : $tipoImovel->nome ?></td>
            <?php endforeach; ?>
            <?php foreach ($tiposImoveis as $tipoImovel) : ?>
            <td><?= $tipoImovel->sigla ? $tipoImovel->sigla : $tipoImovel->nome ?></td>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($dataProvider->models as $row) :

            $dadosImoveis = [];
            $dadosFoco = [];

            $areaFoco = '';
            $cssClass = 'success';

            foreach ($tiposImoveis as $tipoImovel) {
                $dadosImoveis[$tipoImovel->id] = $dadosFoco[$tipoImovel->id] =  0;
            }

            $dadosImoveis['total'] = $dadosFoco['total'] = 0;

            foreach ($row->boletinsFechamento as $boletimFechamento) {

                $quantidade = $boletimFechamento->quantidade;

                if ($boletimFechamento->area_de_foco) {

                    $areaFoco = '@TODO';
                    $cssClass = 'danger';
                    $totalAreasFoco++;

                    $dadosFoco[$boletimFechamento->imovel_tipo_id] = $quantidade;
                    $dadosFoco['total'] += $quantidade;

                    $dadosTotaisFoco[$boletimFechamento->imovel_tipo_id] += $quantidade;
                    $dadosTotaisFoco['total'] += $quantidade;
                }
                $dadosImoveis[$boletimFechamento->imovel_tipo_id] = $quantidade;
                $dadosImoveis['total'] += $quantidade;

                $dadosTotaisImoveis[$boletimFechamento->imovel_tipo_id] += $quantidade;
                $dadosTotaisImoveis['total'] += $quantidade;
            }
            ?>
        <tr class="<?= $cssClass ?>">
            <td><?= $row->quarteirao->numero_quarteirao ?></td>
            <td><?= $row->quarteirao->numero_quarteirao_2 ?></td>
            <td><?= $row->seq ?></td>
            <?php foreach ($dadosImoveis as $info) : ?>
            <td><?= $info ?></td>
            <?php endforeach; ?>
            <td><?= $areaFoco ?></td>
            <?php foreach ($dadosFoco as $info) : ?>
            <td><?= $info ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total:</td>
            <?php foreach ($dadosTotaisImoveis as $info) : ?>
            <td><?= $info ?></td>
            <?php endforeach; ?>
            <td><?= $totalAreasFoco ?></td>
            <?php foreach ($dadosTotaisFoco as $info) : ?>
            <td><?= $info ?></td>
            <?php endforeach; ?>
        </tr>
    </tfoot>
</table>
<?php else : ?>
    <p><?= Yii::t('yii', 'No results found.') ?></p>
<?php endif; ?>
<?php endif; ?>