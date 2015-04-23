<?php
use yii\helpers\Url;
use app\helpers\models\MunicipioHelper;
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Json;

$this->title = 'Focos em ' . $municipio->nome . '/' . $municipio->sigla_estado;

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore', 'markercluster']);
?>

<div class="row">
	<div class="col-md-6">
		<h1>
			<?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>&nbsp;&nbsp;<a href="<?= Url::to(['cidade/index', 'id' => $cliente->id]); ?>"><?= Html::encode($municipio->nome . '/' . $municipio->sigla_estado) ?></a>
		</h1>
	</div>

	<div class="col-md-3">

	</div>

	<div class="col-md-3" style="margin-top: 1em;">
		<div class="text-right">
			<button type="button" class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="bottom" title="Sua denúncia será avaliada pela Prefeitura Municipal e você receberá acesso para acompanhar a resolução">
				<span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
				&nbsp; Faça uma denúncia
			</button>
		</div>
	</div>
</div>

<div class="row">
  	<div class="col-md-12">

		<div class="row" style="margin-bottom: 2em;">
		    <h4 class="text-center" style="font-weight: bold; margin-top: 1em; font-size: 2.5em; margin-top: 0;">
		        Os transmissores da <span style="color: #CC0000; font-size: 1.2em;">Dengue e da Chikungunya</span> vivem perto de você?
		    </h4>
		</div>

		<div id="map" style="height: 500px; width: 100%;"></div>
		<p class="bg-info text-center" style="padding: 0.5em 0;"><strong>Focos dos últimos <?= $qtdeDias; ?> dias</strong></p>

	</div>
</div>

<?php
$municipio->loadCoordenadas();

if($municipio->latitude && $municipio->longitude) {

    $javascript = "
        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = 'pk.eyJ1IjoidmlnaWxhbnR1cyIsImEiOiJXVEZJM1RFIn0.PWHuvfBY6oegZu3R65tWGA';
        var map = L.mapbox.map('map', 'vigilantus.kjkb4j0a');

		var featureGroup = L.featureGroup().addTo(map);

		var search;

        $.geolocation(
            function (lat, lng) {
                map.setView([lat , lng], 15);
                search = L.marker([lat, lng]).addTo(featureGroup);
            },
            function (error) {
                map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);
            }
        );

		var drawControl = new L.Control.Draw({
		    edit: false,
		    draw: {
		        polygon: false,
		        polyline: false,
		        rectangle: false,
		        circle: false,
		        marker: true
		    }
		}).addTo(map);

		map.on('draw:created', function showPolygonArea(e) {
		    featureGroup.clearLayers();
		    featureGroup.addLayer(e.layer);
		    alert('ahuia');
		});

        L.control.fullscreen().addTo(map);

        L.control.scale().addTo(map);

        var markers = new L.MarkerClusterGroup();

        var runLayer = omnivore.kml('" . Url::to($url) . "')
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

            map.addLayer(markers);
        });
    ";

    $this->registerJs($javascript);
}
?>

<style>
.controls {
    margin-top: 16px;
    border: 1px solid transparent;
    border-radius: 2px 0 0 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    height: 32px;
    outline: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
    background-color: #fff;
    padding: 0 11px 0 13px;
    width: 400px;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    text-overflow: ellipsis;
}

#pac-input:focus {
    border-color: #4d90fe;
    margin-left: -1px;
    padding-left: 14px;  /* Regular padding-left + 1. */
    width: 401px;
}
</style>
