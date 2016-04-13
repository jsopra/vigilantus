<?php
use yii\helpers\Html;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use yii\web\JsExpression;
use Yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Casos de DoenÃ§a';
$this->params['breadcrumbs'][] = $this->title;

MapBoxAPIHelper::registerScript($this, ['fullScreen', 'omnivore', 'markercluster']);
?>

<h1 id="stepguide-title"><?= Html::encode($this->title) ?></h1>

<div id="map"  style="height: 450px; width: 100%;"></div>

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

        var markers = new L.MarkerClusterGroup();

        var runLayer = omnivore.kml('" . Url::to(['kml/casos-doenca']) . "')
        .on('ready', function() {
            this.eachLayer(function(layer) {

                var marker = L.marker(new L.LatLng(layer.feature.geometry.coordinates[1] + (Math.random() -.1) / 5000, layer.feature.geometry.coordinates[0] + (Math.random() -.1) / 5000), {
                    icon: L.mapbox.marker.icon({
                        'marker-color': '#fc6a6a',
                        'marker-size': 'small',
                        'marker-symbol': 'hospital'
                    }),
                });

                var popupContent = '<p><strong>Nome do Paciente:</strong> ' + layer.feature.properties.nome_paciente + '</p><p><strong>Data dos sintomas:</strong> ' + layer.feature.properties.data_sintomas + '</p>';

                marker.bindPopup(popupContent, {
                    closeButton: false,
                    minWidth: 320
                });

                markers.addLayer(marker);
            });
        });

        markers.addTo(map);
    ";

    $this->registerJs($javascript);
    ?>
<?php endif; ?>