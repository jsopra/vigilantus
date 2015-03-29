<?php
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['highstock', 'modules/exporting', 'modules/drilldown']);

?>
<br />

<div id="stepguide-indicador-rg-update">
<?php if($ultimaAtualizacao) : ?>
    <div class="bs-callout bs-callout-success">
      <p><span class="glyphicon glyphicon-time" style="font-size: 1em; padding-right: 10px;"></span> Última atualização do relatório em <?= $ultimaAtualizacao; ?>. <?= Html::a(Html::encode("Solicite uma atualização agora"),'/relatorio/update-rg'); ?>.</p>
    </div>
<?php else : ?>
    <div class="bs-callout bs-callout-danger">
      <p><span class="glyphicon glyphicon-time" style="font-size: 1em; padding-right: 10px;"></span> Não existe histórico de atualização para este relatório. <?= Html::a(Html::encode("Solicite uma atualização agora"),'/relatorio/update-rg'); ?>.</p>
    </div>
<?php endif; ?>
</div>

<div id="capa-resumo-rg" class="row">
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="number">Geral</th>
                    <th class="number">Foco</th>
                </tr>
            </thead>
            <tbody>
                <tr class="totalizador">
                    <td>Quarteirões</td>
                    <td class="text-center"><?= $model->getTotalQuarteiroes() ?></td>
                    <td class="text-center"><?= $model->getTotalQuarteiroesFoco() ?></td>
                </tr>
            </tbody>
        </table>
        <?php
        $series = [
            'rg' => [],
            'focos' => [],
        ];
        $tipos = [];
        foreach ($model->getImoveisPorTipo() as $tipo => $dados) {
            $tipos[] = $tipo;
            $series['rg'][] = (int) $dados['imoveis'];
            $series['focos'][] =(int) $dados['focos'];
        }
        ?>

        <?= Highcharts::widget([
           'options' => [
                'title' => ['text' => 'Quarteirões por Tipo de Imóvel'],
                'xAxis' => [
                    'categories' => $tipos,
                ],
                'yAxis' => [
                    'title' => ['text' => 'Qtde de Imóveis'],
                ],
                'tooltip' => ['shared' => true],
                'series' => [
                    [
                        'name' => 'Qtde de Imóveis',
                        'type' => 'column',
                        'data' => $series['rg'],

                    ],
                    [
                        'name' => 'Qtde de Imóveis em Área de Tratamento de Foco',
                        'type' => 'spline',
                        'data' => $series['focos'],
                    ]
                ],
           ]
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bairro</th>
                    <th>Imóveis</th>
                    <th class="number">Foco</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->getImoveisPorBairro() as $bairro => $dados) : ?>
                <tr>
                    <td><?= $bairro ?></td>
                    <td style="text-align: center;"><?= $dados['imoveis'] ?></td>
                    <td style="text-align: center;"><?= $dados['focos'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
