<br />

<?php
use miloschuman\highcharts\Highcharts;

echo Highcharts::widget([
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
                        'click' => new yii\web\JsExpression('function () { window.location.href="' . yii\helpers\Url::to(['OcorrenciaSearch[data_fechamento]' => '0', 'ocorrencia/ocorrencia/index', 'OcorrenciaSearch[qtde_dias_aberto]' => '']) . '" + this.range; }'),
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
