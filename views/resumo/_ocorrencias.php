<?php
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
?>

<br />

<div id="main-stats">
    <div class="row stats-row">
        <div class="col-md-3 col-sm-3 stat">
            <div class="data">
                <span class="number"><?= $resumo->getTotalDenunciasRecebidas(); ?></span>
                recebidas
            </div>
            <span class="date">Em <?= date('Y'); ?></span>
        </div>
        <div class="col-md-3 col-sm-3 stat">
            <div class="data">
                <span class="number"><?= $resumo->getTotalDenunciasFinalizadas(); ?></span>
                finalizadas
            </div>
            <span class="date">Em <?= date('Y'); ?></span>
        </div>
        <div class="col-md-3 col-sm-3 stat">
            <div class="data">
                <span class="number"><?= $resumo->getTotalDenunciasPendentes(); ?></span>
                pendentes
            </div>
            <span class="date">de finalização</span>
        </div>
        <div class="col-md-3 col-sm-3 stat last">
            <div class="data">
                <span class="number"><?= $resumo->getTempoAtendimentoMedio(); ?></span>
                tempo médio
            </div>
            <span class="date">de atendimento em dias</span>
        </div>
    </div>
</div>

<?php if((\Yii::$app->user->can('Analista') || \Yii::$app->user->can('Gerente')) && \Yii::$app->user->getIdentity()->moduloIsHabilitado(\app\models\Modulo::MODULO_OCORRENCIA)) : ?>
    <div class="bs-callout bs-callout-success">
        <p><span class="glyphicon glyphicon-dashboard" style="font-size: 1em; padding-right: 10px;"></span> <?= Html::a(Html::encode("Clique aqui e veja diferentes indicadores de ocorrência"),'/ocorrencia/indicador/ocorrencias-mes'); ?></p>
    </div>
<?php endif; ?>

<br /><br />

<?= Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Ocorrências Abertas'],
      'chart' => ['type' => 'column'],
      'xAxis' => [
         'categories' => [
            'Até ' . $diasVerde . ' dias',
            'Entre ' . $diasVerde . ' e ' . $diasVermelho . ' dias',
            'Mais de ' . $diasVermelho . ' dias',
         ]
      ],
      'yAxis' => [
         'title' => ['text' => 'Quantidade']
      ],
      'plotOptions' => [
            'series' => [
                'cursor' => 'pointer',
                'point' => [
                    'events' => [
                        'click' => new yii\web\JsExpression('function () { window.location.href="' . yii\helpers\Url::to(['OcorrenciaSearch[data_fechamento]' => '0', 'ocorrencia/ocorrencia/abertas', 'OcorrenciaSearch[qtde_dias_aberto]' => '']) . '" + this.range; }'),
                    ]
                ],
            ]
        ],
        'series' => [
            ['name' => 'Quantidade de Ocorrências', 'data' => [
                ['y' => $qtdeVerde, 'color' => '#4FD190', 'range' => 1],
                ['y' => $qtdeAmarelo, 'color' => '#FFFF50', 'range' => 2,],
                ['y' => $qtdeVermelho, 'color' => '#FFA0A0', 'range' => 3],
            ]],
      ]
   ]
]);
?>

<style>
#main-stats {
  background-color: #fdfdfd;
  border-bottom: 1px solid #efeef3; }
  #main-stats .stats-row {
    box-shadow: inset -1px 0px 5px 2px #f9f9f9;
    margin: 0; }
  #main-stats .stat {
    text-align: right;
    padding: 25px 0px 30px 0px;
    border-right: 1px solid #e8e9ee;
    position: relative;
    box-shadow: 1px 0px 0px 0px white; }
    #main-stats .stat.last {
      border-right: 0px; }
    #main-stats .stat .data {
      color: #29323a;
      text-transform: uppercase;
      font-weight: 600;
      font-size: 16px;
      padding-right: 50px; }
      #main-stats .stat .data .number {
        color: #32a0ee;
        font-size: 25px;
        margin-right: 15px; }
    #main-stats .stat .date {
      color: #b4b8bb;
      font-weight: lighter;
      font-family: 'Lato', 'Open Sans';
      font-style: italic;
      font-size: 13px;
      position: absolute;
      right: 50px; }
</style>
