<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use Yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Visão Geral';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore']);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div id="map"  style="height: 450px; width: 100%;">
    <nav id='menu-ui' class='menu-ui'></nav>
</div>

<?php
$municipio = \Yii::$app->session->get('user.cliente')->municipio;
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

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox
            .map('map', 'vigilantus.kjkb4j0a')
            .setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 12)
            .on('ready', function() {
            });

        L.control.fullscreen().addTo(map);

        L.featureGroup().addTo(map);

        L.control.scale().addTo(map);

        var focosLayer = omnivore.kml('" . Url::to(['kml/focos']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#fc6a6a',
                    'marker-size': 'small',
                    'marker-symbol': 'danger'
                }));

            });
        })
        .addTo(map);

        addLayer(focosLayer, 'Focos', 1);

        var bairrosLayer = omnivore.kml('" . Url::to(['kml/cidade']) . "')
        .on('ready', function() {
            this.eachLayer(function(marker) {

                marker.setIcon(L.mapbox.marker.icon({
                    'marker-color': '#fc6a6a',
                    'marker-size': 'small',
                    'marker-symbol': 'danger'
                }));

            });
        })
        .addTo(map);

        addLayer(bairrosLayer, 'Bairros', 2);

        function addLayer(layer, name, zIndex) {
            layer
                .setZIndex(zIndex)
                .addTo(map);

            // Create a simple layer switcher that
            // toggles layers on and off.
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
