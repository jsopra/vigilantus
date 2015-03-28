<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\FocoTransmissor;
use yii\web\JsExpression;
use Yii\helpers\Url;

$this->title = 'Área de Tratamento de Foco';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore']);
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
                            'options' => ['placeholder' => 'Bairro - Quarteirão nº XXX'],
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

        var polyline_related_options = {
            color: '#797979'
        }

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

        L.mapbox.featureLayer({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [quarteiraoCenter.lng, quarteiraoCenter.lat]
            },
            properties: {
                title: '" . $quarteirao->numero_quarteirao . "',
                'marker-color': '#000',
                'marker-symbol': 'hospital'
            }
        }).addTo(map);

        var customLayer = L.geoJson(null, {
            style: function(feature) {
                return {
                    color: '#959391'
                };
            }
        });

        var runLayer = omnivore.kml('" . Url::to(['kml/area-tratamento-foco', 'id' => $foco->id]) . "', null, customLayer)
        .on('ready', function() {
            this.eachLayer(function(layer) {

                var quarteiraoPoligono = L.polygon(layer.feature.geometry.coordinates[0]);
                var quarteiraoCenter = quarteiraoPoligono.getBounds().getCenter();

                L.mapbox.featureLayer({
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [quarteiraoCenter.lat, quarteiraoCenter.lng]
                    },
                    properties: {
                        title: layer.feature.properties.numero_quarteirao,
                        'marker-size': 'small',
                        'marker-color': '#fc6a6a',
                        'marker-symbol': 'roadblock'
                    }
                }).addTo(map);
            });
        })
        .addTo(map);
    ";

    $this->registerJs($javascript);
}
?>
