<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\FocoTransmissor;
use yii\web\JsExpression;

$this->title = 'Área de Tratamento de Foco';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen']);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="tratamento-selecao-foco" data-role="modal-grid">

    <div class="form well">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]); ?>

            <div class="row">

                <div class="col-xs-10">
                    <?
                    $url = \yii\helpers\Url::to(['mapa/focos']);

                    $initScript = '
                    function (element, callback) {
                        var id=$(element).val();
                        if (id !== "") {
                            $.ajax("' . $url . '?id=" + id, {
                                dataType: "json"
                            }).done(function(data) { callback(data[0]);});
                        }
                    }
                    ';

                    echo $form->field($model, 'foco_id')->widget(
                        Select2::classname(),
                        [
                            'options' => ['placeholder' => 'Bairro - Quarteirão nº XXX (XX/XX/XXXX)'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'delay' => 250,
                                    'data' => new JsExpression('function(term,page) { return {q:term}; }'),
                                    'results' => new JsExpression('function(data,page) { return {results:data}; }'),
                                ],
                                'initSelection' => $foco ? new JsExpression($initScript) : null,
                            ],
                        ]
                    );
                    ?>
                </div>

                <div class="col-xs-2" style="padding-top: 20px;">
                    <?= Html::submitButton('Gerar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<br />


<div id="map"  style="height: 450px; width: 100%;"></div>

<?php
if($foco) {
    $quarteirao = $foco->bairroQuarteirao;
    $quarteirao->loadCoordenadas();

    $javascript = "
        var line_points = " . MapHelper::getArrayCoordenadas($quarteirao->coordenadas) . ";
        var polyline_options = {
            color: '#000'
        };

        var quarteiraoPoligono = L.polygon(line_points, polyline_options);
        var quarteiraoCenter = quarteiraoPoligono.getBounds().getCenter();

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView(quarteiraoCenter, 15);

        L.control.fullscreen().addTo(map);
        L.featureGroup().addTo(map);

        quarteiraoPoligono.addTo(map);
        L.circle(quarteiraoCenter, " . $foco->especieTransmissor->qtde_metros_area_foco . ").addTo(map);

        L.control.scale().addTo(map);
    ";

    if($areaTratamento) {

        $javascript .= "
            var areaTratamentoLayer = L.mapbox.featureLayer().addTo(map);
            var areaTratamento = [];
        ";

        foreach($areaTratamento as $quarteirao) {

            $quarteirao->loadCoordenadas();

            $javascript .= "

                var line_points" . $quarteirao->id . " = " . MapHelper::getArrayCoordenadas($quarteirao->coordenadas) . ";

                var quarteiraoPoligono" . $quarteirao->id . " = L.polygon(line_points" . $quarteirao->id . ", polyline_options);
                var quarteiraoCenter" . $quarteirao->id . " = quarteiraoPoligono" . $quarteirao->id . ".getBounds().getCenter();

                areaTratamento.push({
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [quarteiraoCenter" . $quarteirao->id . ".lng, quarteiraoCenter" . $quarteirao->id . ".lat]
                    },
                    properties: {
                        'marker-color': '#000',
                        'marker-symbol': 'danger',
                        title: '" . $quarteirao->numero_quarteirao . "',
                    }
                });
            ";
        }

        $javascript .= "
            areaTratamentoLayer.setGeoJSON({
                type: 'FeatureCollection',
                features: areaTratamento
            });
        ";
    }

    $this->registerJs($javascript);
}
?>
