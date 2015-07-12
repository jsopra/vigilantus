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
      <p><span class="glyphicon glyphicon-time" style="font-size: 1em; padding-right: 10px;"></span> Última atualização do mapa em <?= $ultimaAtualizacao; ?>. <?= Html::a(Html::encode("Solicite uma atualização agora"),'/relatorio/update-focos'); ?>.</p>
    </div>
<?php else : ?>
    <div class="bs-callout bs-callout-danger">
      <p><span class="glyphicon glyphicon-time" style="font-size: 1em; padding-right: 10px;"></span> Não existe histórico de atualização para o mapa. <?= Html::a(Html::encode("Solicite uma atualização agora"),'/relatorio/update-focos'); ?>.</p>
    </div>
<?php endif; ?>
</div>

<?php if(\Yii::$app->user->can('Analista') || \Yii::$app->user->can('Gerente')) : ?>
    <div class="bs-callout bs-callout-success">
        <p><span class="glyphicon glyphicon-dashboard" style="font-size: 1em; padding-right: 10px;"></span> <?= Html::a(Html::encode("Clique aqui e veja diferentes indicadores de focos"),'/indicador/resumo-focos'); ?></p>
    </div>
<?php endif; ?>

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
