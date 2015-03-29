<?php
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['highstock', 'modules/exporting', 'modules/drilldown']);
?>

<br />

<div id="capa-tipo-deposito-focos" class="row">

    <div class="col-md-8">
        <h4>Por Tipo de Depósito</h4>

        <br />

        <?php
            $tipos = [];
            $series = [];

            foreach($model->getEspecieTransmissor() as $especie) {
                $series[] = [
                    'name' => $especie->nome,
                    'data' => [],
                ];
            }

            foreach($model->getTiposDepositos() as $tipo) {
                $tipos[] = $tipo->descricao;
            }

            foreach($model->getTiposDepositos() as $tipo) {

                $i = 0;
                foreach ($model->getEspecieTransmissor() as $especie)  {

                    $series[$i]['data'][] = [
                        'y' => $model->getQuantidadeFocosTipoDeposito(date('Y'), $especie->id, $tipo->id),
                        'perc' => $model->getPercentualFocosTipoDeposito(date('Y'), $especie->id, $tipo->id),
                    ];

                    $i++;
                }
            }
        ?>

        <?= Highcharts::widget([
           'options' => [
                'chart' => ['type' => 'column'],
                'title' => ['text' => ''],
                'xAxis' => [
                    'categories' => $tipos,
                ],
                'yAxis' => [
                    'min' => 0,
                    'title' => ['text' => 'Qtde de Focos'],
                ],
                'tooltip' => [
                    'pointFormat' => '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.perc:.2f}%)<br/>',
                    'shared' => true
                ],
                'plotOptions' => [
                    'column' => ['stacking' => 'number'],
                ],
                'series' => $series
           ]
        ]);
        ?>
    </div>

    <div class="col-md-4">
        <h4>Por Forma de Foco</h4>

        <br />

        <?php
            $tipos = [];
            $series = [];

            foreach($model->getEspecieTransmissor() as $especie) {
                $series[] = [
                    'name' => $especie->nome,
                    'data' => [],
                ];
            }

            foreach($model->getFormasFoco() as $id => $tipo) {
                $tipos[] = $tipo;
            }

            foreach($model->getFormasFoco() as $id => $tipo) {

                $i = 0;
                foreach ($model->getEspecieTransmissor() as $especie)  {

                    $series[$i]['data'][] = [
                        'y' => $model->getQuantidadeFocosFormaFoco(date('Y'), $especie->id, $id),
                        'perc' => $model->getPercentualFocosFormaFoco(date('Y'), $especie->id, $id),
                    ];

                    $i++;
                }
            }
        ?>

        <?= Highcharts::widget([
           'options' => [
                'chart' => ['type' => 'bar'],
                'title' => ['text' => ''],
                'xAxis' => [
                    'categories' => $tipos,
                ],
                'yAxis' => [
                    'min' => 0,
                    'title' => ['text' => 'Qtde de Focos'],
                ],
                'tooltip' => [
                    'pointFormat' => '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.perc:.2f}%)<br/>',
                    'shared' => true
                ],
                'plotOptions' => [
                    'column' => ['stacking' => 'number'],
                ],
                'series' => $series
           ]
        ]);
        ?>
    </div>
</div>
