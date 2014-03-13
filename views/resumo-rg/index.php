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
<div class="resumo-rg-index" data-role="modal-grid">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

</div>

<?php if ($searchModel->bairro_id) : ?>
<?php if ($dataProvider->getTotalCount()) : ?>
<table id="resumo-rg-bairro" class="table table-striped table-condensed table-bordered">
    <?php
    $dadosTotaisImoveis = $dadosTotaisFoco = [];

    foreach ($tiposImoveis as $tipoImovel) {
        $dadosTotaisImoveis[$tipoImovel->id] = $dadosTotaisFoco[$tipoImovel->id] =  0;
    }

    $dadosTotaisImoveis['total'] = $dadosTotaisFoco['total'] = 0;
    ?>
    <thead>
        <tr>
            <th rowspan="2" class="number">Nº Quart.</th>
            <th rowspan="2" class="number">Nº Alt.</th>
            <th rowspan="2" class="number">Seq.</th>
            <th colspan="<?= count($tiposImoveis) ?>">TIPO DO IMÓVEL</th>
            <th rowspan="2" class="total">Total de Imóveis</th>
            <th colspan="<?= count($tiposImoveis) ?>">LIRA</th>
            <th rowspan="2" class="total">Total de Imóveis</th>
        </tr>
        <tr>
            <?php foreach ($tiposImoveis as $tipoImovel) : ?>
            <th><?= $tipoImovel->sigla ? $tipoImovel->sigla : $tipoImovel->nome ?></th>
            <?php endforeach; ?>
            <?php foreach ($tiposImoveis as $tipoImovel) : ?>
            <th><?= $tipoImovel->sigla ? $tipoImovel->sigla : $tipoImovel->nome ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($dataProvider->models as $row) :

            $dadosImoveis = [];
            $dadosLira = [];
            $dadosTotaisLira = [];

            $cssClass = 'success';

            foreach ($tiposImoveis as $tipoImovel) {
                $dadosImoveis[$tipoImovel->id] = $dadosLira[$tipoImovel->id] = $dadosTotaisLira[$tipoImovel->id] = 0;
            }

            $dadosImoveis['total'] = $dadosLira['total'] = $dadosTotaisLira['total'] = 0;

            foreach ($row->boletinsFechamento as $boletimFechamento) {

                $quantidade = $boletimFechamento->quantidade;

                if ($boletimFechamento->imovel_lira) {

                    $cssClass = 'danger';

                    $dadosLira[$boletimFechamento->imovel_tipo_id] += $quantidade;
                    $dadosLira['total'] += $quantidade;

                    $dadosTotaisLira[$boletimFechamento->imovel_tipo_id] += $quantidade;
                    $dadosTotaisLira['total'] += $quantidade;
                }
                
                $dadosImoveis[$boletimFechamento->imovel_tipo_id] += $quantidade;
                $dadosImoveis['total'] += $quantidade;

                $dadosTotaisImoveis[$boletimFechamento->imovel_tipo_id] += $quantidade;
                $dadosTotaisImoveis['total'] += $quantidade;
            }
            ?>
        <tr class="<?= $cssClass ?>">
            <td style="text-align: center;"><?= $row->quarteirao->numero_quarteirao ?></td>
            <td style="text-align: center;"><?= $row->quarteirao->numero_quarteirao_2 ?></td>
            <td style="text-align: center;"><?= $row->seq ?></td>
            <?php foreach ($dadosImoveis as $key => $info) : ?>
            <td style="text-align: center;"><?= $key == 'total' ? Html::tag('strong', $info) : $info ?></td>
            <?php endforeach; ?>

            <?php foreach ($dadosLira as $key => $info) : ?>
            <td style="text-align: center;"><?= $key == 'total' ? Html::tag('strong', $info) : $info ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total:</th>
            <?php foreach ($dadosTotaisImoveis as $key => $info) : ?>
            <td style="text-align: center;"><?= $key == 'total' ? Html::tag('strong', $info) : $info ?></td>
            <?php endforeach; ?>
            <?php foreach ($dadosTotaisLira as $key => $info) : ?>
            <td style="text-align: center;"><?= $key == 'total' ? Html::tag('strong', $info) : $info ?></td>
            <?php endforeach; ?>
        </tr>
    </tfoot>
</table>
<?php else : ?>
    <p><?= Yii::t('yii', 'No results found.') ?></p>
<?php endif; ?>
<?php else : ?>
    <?= $this->render('_capa', ['resumoBairros' => $resumoBairros, 'resumoTiposImoveis' => $resumoTiposImoveis]); ?>
<?php endif; ?>