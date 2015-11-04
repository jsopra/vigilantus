<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use Yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Visão Geral';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore', 'markercluster']);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div id="map"  style="height: 450px; width: 100%;">
    <nav id='menu-ui' class='menu-ui'></nav>
</div>

<?php
$municipio = \Yii::$app->user->identity->cliente->municipio;
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <?php
    $javascript = "
        var layers = document.getElementById('menu-ui');
        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
        var map = L.mapbox
            .map('map', '" . Yii::$app->params['mapBoxMapID'] . "')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 12)
            .on('ready', function() {
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var markers = new L.MarkerClusterGroup();

        var runLayer = omnivore.kml('" . Url::to(['kml/focos']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                var marker = L.marker(new L.LatLng(marker.feature.geometry.coordinates[1], marker.feature.geometry.coordinates[0]), {
                    icon: L.mapbox.marker.icon({
                        'marker-color': '#fc6a6a',
                        'marker-size': 'small',
                        'marker-symbol': 'hospital'
                    }),
                });
                markers.addLayer(marker);
            });
        });

        addLayer(markers, 'Focos', 1);

        var bairrosLayer = omnivore.kml('" . Url::to(['kml/cidade']) . "').addTo(map);
        addLayer(bairrosLayer, 'Bairros', 2);

        var bairrosLayer = omnivore.kml('" . Url::to(['kml/armadilha']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#C8DF9F',
                    'marker-size': 'small',
                    'marker-symbol': 'chemist'
                }));

            });
        })
        .addTo(map);
        addLayer(bairrosLayer, 'Armadilhas', 3);

        var bairrosLayer = omnivore.kml('" . Url::to(['kml/ponto-estrategico']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#BBBBDC',
                    'marker-size': 'small',
                    'marker-symbol': 'golf'
                }));

            });
        })
        .addTo(map);
        addLayer(bairrosLayer, 'Pontos Estratégicos', 4);

        var ocorrenciasLayer = omnivore.kml('" . Url::to(['kml/ocorrencias']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#fc6a6a',
                    'marker-size': 'small',
                    'marker-symbol': 'embassy'
                }));

            });
        })
        .addTo(map);
        addLayer(ocorrenciasLayer, 'Ocorrências', 5);

        function addLayer(layer, name, zIndex) {
            layer
                .setZIndex(zIndex)
                .addTo(map);

            var link = document.createElement('a');
                link.href = '#';
                link.className = 'active';
                link.innerHTML = name;

            link.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (map.hasLayer(layer)) {
                    map.removeLayer(layer);
                    this.className = '';
                } else {
                    map.addLayer(layer);
                    this.className = 'active';
                }
            };

            layers.appendChild(link);
        }
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>
