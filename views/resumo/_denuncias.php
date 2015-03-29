<br />

<?php
use miloschuman\highcharts\Highcharts;

echo Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Denúncias Abertas'],
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
                        'click' => new yii\web\JsExpression('function () { window.location.href="' . yii\helpers\Url::to(['DenunciaSearch[data_fechamento]' => '0', 'denuncia/denuncia/index', 'DenunciaSearch[qtde_dias_aberto]' => '']) . '" + this.range; }'),
                    ]
                ],
            ]
        ],
        'series' => [
            ['name' => 'Quantidade de Denúncias', 'data' => [
                ['y' => $qtdeVerde, 'color' => '#4FD190', 'range' => 1],
                ['y' => $qtdeAmarelo, 'color' => '#FFFF50', 'range' => 2,],
                ['y' => $qtdeVermelho, 'color' => '#FFA0A0', 'range' => 3],
            ]],
      ]
   ]
]);
