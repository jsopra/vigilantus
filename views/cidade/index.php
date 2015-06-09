<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Json;

$this->title = 'Focos em ' . $municipio->nome . '/' . $municipio->sigla_estado;
$urlOcorrencia = Url::to('/' . $cliente->rotulo, true);
$descricaoPagina = 'Acabei de denunciar um foco da dengue para a Prefeitura de ' . $municipio->nome . ' - ' . $municipio->sigla_estado . '. Veja as dicas para combater a doença e caso perceba qualquer problema, denuncie em ' .  $urlOcorrencia;
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::to('/img/og-sharing-preview.jpg', true)]);
$this->registerMetaTag(['property' => 'og:title', 'content' => 'Denuncie focos de mosquitos da dengue']);
$this->registerMetaTag(['property' => 'og:description', 'content' => $descricaoPagina]);

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore', 'markercluster']);
?>

<?= $this->render('_cidadeHeader', ['municipio' => $municipio, 'cliente' => $cliente, 'button' => '_buttonOcorrencia']); ?>

<div class="panel panel-default" style="margin-top: 2.5em;">

    <div class="panel-heading focos">
        <h4 class="text-center" style="font-weight: bold; margin-top: 1em; font-size: 2.5em; margin-top: 0;">
            Os transmissores da <span style="color: #CC0000; font-size: 1.2em;">Dengue e da Chikungunya</span> vivem perto de você?
        </h4>
    </div>

    <div class="row">
      	<div class="col-md-12">

            <p class="bg-info text-center" style="padding: 0.5em 0; margin: 1em 0 0 0;"><strong>Focos dos últimos <?= $qtdeDias; ?> dias</strong></p>
    		<div id="map" style="height: 500px; width: 100%;">
                <nav id='menu-ui' class='menu-ui'></nav>
            </div>
    	</div>
    </div>

</div>

<?php
$municipio->loadCoordenadas();

if($municipio->latitude && $municipio->longitude) {

    $javascript = "
        var layers = document.getElementById('menu-ui');

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

                $.getJSON('" . Url::to(['cidade/coordenada-na-cidade', 'id' => $cliente->id]) . "&lat=' + lat + '&lon=' + lng, function(data) {
                    if(data.coordenadaNaCidade) {

                        map.setView([lat , lng], 15);
                        search = L.marker([lat, lng]).addTo(featureGroup);
                        verificaAreaTratamento(lat, lng);

                    } else {
                        map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);

                        $.toast({
                            heading: 'Fora da cidade',
                            text: 'Você não está nesta cidade. Sua localização foi descartada.',
                            position: 'top-right',
                            stack: false,
                            icon: 'info'
                        });
                    }
                });


            },
            function (error) {
                map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);
            }
        );

		var drawControl = new L.Control.Draw({
            position: 'topright',
		    edit: false,
		    draw: {
		        polygon: false,
		        polyline: false,
		        rectangle: false,
		        circle: false,
		        marker: false
		    }
		}).addTo(map);

        L.drawLocal.draw.handlers.marker.tooltip.start = 'Selecione um local e saiba se ele está em área de risco';

        var markerDrawer = new L.Draw.Marker(map, drawControl.options.marker);

		map.on('draw:created', function showPolygonArea(e) {

		    featureGroup.clearLayers();
		    featureGroup.addLayer(e.layer);
            verificaAreaTratamento(e.layer.toGeoJSON().geometry.coordinates[1], e.layer.toGeoJSON().geometry.coordinates[0]);
		});

        L.control.fullscreen().addTo(map);

        L.control.scale().addTo(map);

        var markers = new L.MarkerClusterGroup();

        var runLayer = omnivore.kml('" . Url::to(['kml/focos', 'clienteId' => $cliente->id, 'informacaoPublica' => true]) . "')
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

        function verificaAreaTratamento(lat, lon) {

            $.getJSON('" . Url::to(['cidade/is-area-tratamento', 'id' => $cliente->id]) . "&lat=' + lat + '&lon=' + lon, function(data) {

                if(data.isAreaTratamento == true) {
                    $.toast({
                        heading: 'Em área de risco!',
                        text: 'O ponto está em área de tratamento! Denuncie qualquer irregularidade!',
                        position: 'top-right',
                        stack: false,
                        icon: 'error'
                    });
                } else {
                    $.toast({
                        heading: 'Fora de área de risco',
                        text: 'O ponto não está em área de tratamento!',
                        position: 'top-right',
                        stack: false,
                        icon: 'info'
                    });
                }
            });
        }

        addButton('Verificar um local', 1);

        function addButton(name, zIndex) {

            var link = document.createElement('a');
                link.href = '#';
                link.className = 'active';
                link.innerHTML = name;

            link.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();

                markerDrawer.enable();
            };

            layers.appendChild(link);
        }
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
