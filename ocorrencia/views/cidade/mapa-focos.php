<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Configuracao;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\MapHelper;
use perspectivain\mapbox\MapBoxAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Json;
use app\helpers\models\MunicipioHelper;

$this->title = 'Focos em ' . Html::encode($municipio->nome . '/' . $municipio->sigla_estado);

$urlOcorrencia = Url::to('/' . $municipio->slug . '/mapa-focos-dengue', true);
$urlCompartilhar = Url::to('/' . $municipio->slug . '/mapa-focos-dengue', true);

MapBoxAPIHelper::registerScript($this, ['drawing', 'fullScreen', 'minimap', 'omnivore', 'markercluster']);

$this->registerMetaTag(['property' => 'og:image', 'content' => Url::to('/img/og-sharing-map.jpg', true)]);
$this->registerMetaTag(['property' => 'og:title', 'content' => 'Denuncie focos de mosquitos da dengue']);

$tratamentoMessage = 'Verifique se o local onde você mora';
if($emAreaTratamento === false) {
    $tratamentoMessage = 'Meu local não';
} else if ($emAreaTratamento === true) {
    $tratamentoMessage = 'Meu local';
}

$this->registerMetaTag(['property' => 'og:description', 'content' => $tratamentoMessage . ' está em área de tratamento de Dengue. Denuncie qualquer irregularidade! Seja a mudança na nossa cidade! Faça seu contato em ' . $urlOcorrencia]);
?>

<h1 class="text-xs-center">
    <?= MunicipioHelper::getBrasaoAsImageTag($municipio, 'small'); ?>
    <a href="<?= Url::to(['view', 'slug' => $municipio->slug]); ?>">
        <?= Html::encode($municipio->nome . ' / ' . $municipio->sigla_estado) ?>
    </a>
    <?php if ($setor = $municipio->getSetorResponsavel()) : ?>
    <?= Html::encode($setor) ?>
    <?php endif; ?>
</h1>

<a name="sharetext"></a>

<p class="text-xs-center bloco-botoes-ocorrencias">
    <a href="<?= Url::to(['registrar-ocorrencia/index', 'slug' => $municipio->slug]) ?>" class="btn btn-primary btn-lg">
        <i class="fa fa-plus"></i>
        registrar ocorrência
    </a>
    <a href="<?= Url::to(['buscar-ocorrencia', 'slug' => $municipio->slug]) ?>" class="btn btn-default btn-secondary btn-lg">
        <i class="fa fa-eye"></i>
        acompanhar ocorrência
    </a>
</p>

<div class="texto-compartilhar alert alert-info" style="display:none">
    <p class="text-xs-center" style="line-height: 1.8em; color: #CC0000; font-size: 2em;">
        <span id="texto-compartilhar-frase"> O ponto está em área de tratamento! Denuncie qualquer irregularidade!</span>
        <div class="text-center">
            <div class="fb-share-button" data-href="<?= $urlOcorrencia ?>" data-layout="button"></div>
            <div id="tweetButton" style="display: inline"></div>
        </div>
    </p>
</div>

<p class="text-xs-center">
    Os transmissores da <strong>Dengue</strong> e do
    <strong>Zika Vírus</strong> vivem perto de você?
</p>

<div class="row">
  	<div class="col-md-12">
        <p class="bg-info text-xs-center" style="padding: 0.5em 0; margin: 1em 0 0 0;">
            <strong>Focos dos últimos <?= $qtdeDias; ?> dias</strong>
        </p>
        <div id="map-loader">
            <div id="centro">
                <button class="btn btn-default btn-secondary">
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                    Carregando...
                </button>
            </div>
        </div>
		<div id="map" style="height: 500px; width: 100%;">
            <nav id='menu-ui' class='menu-ui'></nav>
        </div>
	</div>
</div>


<?php
$municipio->loadCoordenadas();

if ($municipio->latitude && $municipio->longitude) {

    $javascript = "

        $('#map-loader').show();

        var layers = document.getElementById('menu-ui');

        var line_points = " . Json::encode([]) . ";
        var polyline_options = {
            color: '#000'
        };

        L.mapbox.accessToken = '" . Yii::$app->params['mapBoxAccessToken'] . "';
        var map = L.mapbox.map('map', '" . Yii::$app->params['mapBoxMapID'] . "');

		var featureGroup = L.featureGroup().addTo(map);

		var search;

        map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);
    ";

    if(!$lat || !$lon) {
        $javascript .= "

            $.geolocation(
                function (lat, lng) {
                    verificaCoordenadaCidade(lat, lng);
                },
                function (error) {
                    map.setView([" . $municipio->latitude . " , " . $municipio->longitude . "], 13);
                }
            );
        ";
    } else {
        $javascript .= "
            verificaCoordenadaCidade(" . $lat . ", " . $lon . ");
        ";
    }

    $javascript .= "
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

        L.drawLocal.draw.handlers.marker.tooltip.start = 'Selecione um local e saiba se ele está em área de tratamento da Dengue';

        var markerDrawer = new L.Draw.Marker(map, drawControl.options.marker);

		map.on('draw:created', function showPolygonArea(e) {

		    featureGroup.clearLayers();
		    featureGroup.addLayer(e.layer);
            verificaAreaTratamento(e.layer.toGeoJSON().geometry.coordinates[1], e.layer.toGeoJSON().geometry.coordinates[0]);
		});

        L.control.fullscreen().addTo(map);

        L.control.scale().addTo(map);

        var markers = new L.MarkerClusterGroup();

        var runLayer = omnivore.kml('" . Url::to(['/kml/focos', 'clienteId' => $cliente->id, 'informacaoPublica' => true]) . "')
        .on('ready', function() {
            $('#map').show();
            $('#map-loader').hide();
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

            $.getJSON('" . Url::to(['/' . $municipio->slug . '/is-area-tratamento']) . "/' + lat + '/' + lon, function(data) {

                if(data.isAreaTratamento == true) {
                    mostraDivCompartilhar('O ponto está em área de tratamento! Denuncie qualquer irregularidade!', 'Achei um local em área de tratamento de Dengue. Denuncie em', lat, lon);
                } else {
                    mostraDivCompartilhar('O ponto não está em área de tratamento!', 'Achei um local que não está em área de tratamento de Dengue. Denuncie em', lat, lon);
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

        function mostraDivCompartilhar(texto, textoTwitter, lat, lon) {
            var urlCompartilhar = '" . $urlCompartilhar . "/' + parseFloat(lat).toFixed(4) + '/' + parseFloat(lon).toFixed(4);
            tweetButton(urlCompartilhar, textoTwitter);
            $('fb-share-button').attr('data-href', urlCompartilhar);

            $('#texto-compartilhar-frase').text(texto);
            $('.texto-compartilhar').show();
            window.location.href = '#sharetext';
        }

        function tweetButton(url, text) {
            $('#tweetButton').html('<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"'+url+'\" data-text=\"'+text+'\" data-count=\"none\" data-lang=\"pt\">Tweet</a>');
            twttr.widgets.load();
        }

        function verificaCoordenadaCidade(lat, lng) {

            $.getJSON('" . Url::to(['/' . $municipio->slug . '/coordenada-na-cidade']) . "/' + lat + '/' + lng, function(data) {
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
        }
    ";
    $this->registerJs($javascript);
}
?>
<div class="well">
    <p class="bloco-legenda-mapa">Legenda</p>
    <table class="table table-hover">
        <tr>
            <td style="width: 90px" align="center"><img src="/img/estouaqui.png"></td>
            <td>Onde estou</td>
            <td align="center"><img src="/img/focotratamento.png"></td>
            <td>Foco em tratamento</td>
            <td align="center"><img src="/img/areaselecionada.png"></td>
            <td>Área selecionada</td>
        </tr>
        <tr>
            <td  align="center"><img src="/img/menor.png"></td>
            <td>Agrupamento de poucos focos</td>
            <td align="center"><img src="/img/medio.png"></td>
            <td>Agrupamento de alguns focos</td>
            <td align="center"><img src="/img/maior.png"></td>
            <td>Agrupamento de muitos focos</td>
        </tr>
    </table>
</div>
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
