<?php
use yii\helpers\Html;
use app\models\Bairro;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\helpers\GoogleMapsAPIHelper;
use app\models\redis\FocosAtivos;
use yii\helpers\Url;

$this->title = 'Focos em ' . $municipio->nome . '/' . $municipio->sigla_estado;
?>

<script src="<?= GoogleMapsAPIHelper::getAPIUrl(false, 'places'); ?>"></script>

<div class="row" style="margin-bottom: 2em;">
    <h4 class="text-center" style="font-weight: bold; margin-top: 1em; font-size: 2.5em;">
        Os transmissores da <span style="color: #CC0000; font-size: 1.2em;">Dengue e da Chikungunya</span> vivem perto de você?
    </h4>
</div>
        
<?php
$municipio->loadCoordenadas();
?>

<?php if($municipio->latitude && $municipio->longitude) : ?>
    <script>

        function initialize() {
            var markers = [];

            var defaultZoom = 13;
                
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: defaultZoom,
                center: new google.maps.LatLng(<?= $municipio->latitude; ?>, <?= $municipio->longitude; ?>),
                mapTypeId: google.maps.MapTypeId.HYBRID,
                disableDefaultUI: false,
                zoomControl: true
            });  

            <?php 
            $qtdeQuarteiroes = count($dados);
            if ($qtdeQuarteiroes > 0) : ?>
                    var quarteiraoColor = '#000000';
                <?php 
                $i = 0;
                foreach($dados as $foco) : 
     
                    $quarteiraoCoordenada = $foco->getQuarteiraoCoordenadas();
                    
                    $corFoco = $foco->cor_foco;
                    
                    $centroQuarteirao = $foco->getCentroQuarteirao();
                    ?>

                    var quarteiraoPolygon<?= $i; ?> = new google.maps.Polygon({
                        paths: [<?= GoogleMapsAPIHelper::arrayToBounds($quarteiraoCoordenada); ?>],
                        strokeWeight: 0,
                        fillColor: quarteiraoColor,
                        fillOpacity: 0.45,
                        map: map
                    });

                    var options<?= $i; ?> = {
                        strokeColor: '<?= $corFoco; ?>',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '<?= $corFoco; ?>',
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(<?= $centroQuarteirao[0]; ?>, <?= $centroQuarteirao[1]; ?>),
                        radius: <?= $foco->qtde_metros_area_foco; ?>
                    };

                    var circle<?= $i; ?> = new google.maps.Circle(options<?= $i; ?>);
                    
                    <?php $i++; ?>
                        
                <?php endforeach; ?>
            <?php endif; ?>

            var input = /** @type {HTMLInputElement} */(document.getElementById('pac-input'));

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var searchBox = new google.maps.places.SearchBox(/** @type {HTMLInputElement} */(input));

            google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            for (var i = 0, marker; marker = markers[i]; i++) {
                marker.setMap(null);
            }

            markers = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
                var image = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                var marker = new google.maps.Marker({
                    map: map,
                    icon: image,
                    title: place.name,
                    position: place.geometry.location
                });

                markers.push(marker);

                bounds.extend(place.geometry.location);
            }

            map.fitBounds(bounds);
            });

            google.maps.event.addListener(map, 'bounds_changed', function() {
                var bounds = map.getBounds();
                searchBox.setBounds(bounds);
            });
          }

          google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<?php endif; ?>

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

<input id="pac-input" class="controls" type="text" placeholder="Digite endereço..." />
<div id="map" style="height: 500px; width: 100%;"></div>